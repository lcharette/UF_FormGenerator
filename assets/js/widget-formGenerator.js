/**
 * @file This file contains functions and bindings for the UserFrosting project management pages.
 *
 * @author Alex Weissman
 * @license MIT
 */

/**
 * Display a modal form for updating/creating a project.
 *
 * @todo This function is highly redundant with userForm.  Can we refactor?
 */
function formGeneratorDisplay(button) {

	var box_id = $(button).data('target');
	if (box_id == undefined) {
		box_id = "formGeneratorModal";
	}

	// Delete any existing instance of the form with the same name
	if($('#' + box_id).length ) {
		$('#' + box_id).remove();
	}

	// Fetch and render the form
	$.ajax({
	  type: "GET",
	  url: $(button).data('formurl'),
	  data: {
		box_id: box_id,
		bData: $(button).data()
	  },
	  cache: false
	})
	.fail(function(result) {
        // Display errors on failure
        console.log("Error (" + result.status + "): " + result.responseText );
        $("#alerts-main").ufAlerts('clear').ufAlerts('fetch').ufAlerts('render');
	})
	.done(function(result) {

        // Append the form as a modal dialog to the body
		$( "body" ).append(result);
		$('#' + box_id).modal('show');

		// Setup the loaded form with ufForm
		$('#' + box_id).find("form").ufForm({
            validators: validators,
            msgTarget: $("#"+box_id+"_alert")
        }).on("submitSuccess.ufForm", function() {
            // Forward to settings page on success
            window.location.reload(true);
        }).on("submitError.ufForm", function() {
            $("#"+box_id+"_alert").show();
        });
	});
}

function formGeneratorConfirm(button){

	var box_id = $(button).data('target');
	if (box_id == undefined) {
		box_id = "formGeneratorModal";
	}

	// Delete any existing instance of the form with the same name
	if($('#' + box_id).length ) {
		$('#' + box_id).remove();
	}

	//Prepare the data
	var data = {
		box_id: box_id,
		box_title: $(button).data('confirmTitle') ? $(button).data('confirmTitle') : "Deleting",
		confirm_message: $(button).data('confirmMessage') ? $(button).data('confirmMessage') : "Are you sure you want to delete this?",
		confirm_button: $(button).data('confirmButton') ? $(button).data('confirmButton') : "Yes, delete",
		bData: $(button).data()
	};

	// Generate the form
	$.ajax({
	  type: "GET",
	  url: $(button).data('formurl') ? $(button).data('formurl') : site['uri']['public'] + "/forms/confirm",
	  data: data
	})
	.fail(function(result) {
        // Display errors on failure
        console.log("Error (" + result.status + "): " + result.responseText );
        $("#alerts-main").ufAlerts('clear').ufAlerts('fetch').ufAlerts('render');
	})
	.done(function(result) {
		// Append the form as a modal dialog to the body
		$( "body" ).append(result);
		$('#' + box_id).modal('show');
		$('#' + box_id + ' .js-confirm').click(function(){

            var url = $(button).data('postUrl');
            var data = {
                bData: $(button).data(),
                csrf_name: $('#' + box_id).find("input[name='csrf_name']").val(),
                csrf_value: $('#' + box_id).find("input[name='csrf_value']").val()
            };

            $.ajax({
              type: "POST",
              url: url,
              data: data
            }).done(function(result) {
              // Reload the page
              window.location.reload();
            }).fail(function(jqXHR) {
                if (site['debug'] == true) {
                    document.body.innerHTML = jqXHR.responseText;
                } else {
                    console.log("Error (" + jqXHR.status + "): " + jqXHR.responseText );
                }
                //base.options.msgTarget.ufAlerts().ufAlerts('fetch').ufAlerts('render');
/*
                $('#userfrosting-alerts').flashAlerts().done(function() {
                    // Close the dialog
                    $('#' + box_id).modal('hide');
                });
*/
            });
        });
	});
}
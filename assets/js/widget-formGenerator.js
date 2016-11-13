/*!
 * FormGenerator Plugin
 *
 * JQuery plugin for the UserFrosting FormGenerator Sprinkle
 * Based on UserFrosting v3
 *
 * @package UF_FormGenerator
 * @author Louis Charette
 * @link https://github.com/lcharette/UF_FormGenerator
 * @license MIT
 */

(function( $ ){

    'use strict';

    var options = {};

    var methods = {

        /*
         * DISPLAY/default Method
         */
        display : function(optionsArg) {

            options = $.extend( options, $.fn.formGenerator.defaultOptions, optionsArg );

            this.each(function() {
                $(this).on('click', formGeneratorDisplay);
            });

            return;
        },

        /*
         * CONFIRM Method
         */
        confirm : function(optionsArg) {

            options = $.extend( options, $.fn.formGenerator.defaultOptions, optionsArg );

            this.each(function() {
                $(this).on('click', formGeneratorConfirm);
            });
        }
    };

    /**
     * formGeneratorDisplay function.
     * Display a modal form based on the info found in
     * the button or link data attributes
     *
     * @access public
     * @param mixed button
     * @return void
     */
    function formGeneratorDisplay(button) {

        var button = this;

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
            options.mainAlertElement.ufAlerts('clear').ufAlerts('fetch').ufAlerts('render');
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

    /**

     */
    /**
     * formGeneratorConfirm function.
     * Display a modal confirmation form based on the info found in
     * the button or link data attributes
     *
     * @access public
     * @return void
     */
    function formGeneratorConfirm(){

        var button = this;

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
            options.mainAlertElement.ufAlerts('clear').ufAlerts('fetch').ufAlerts('render');
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
                    options.mainAlertElement.ufAlerts('clear').ufAlerts('fetch').ufAlerts('render');

                    // Close the dialog
                    $('#' + box_id).modal('hide');
                });
            });
    	});
    }

    /*
     * Main plugin function
     */
    $.fn.formGenerator = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.display.apply( this, arguments );
        } else {
            $.error( 'Method ' +  methodOrOptions + ' does not exist on jQuery.bordereauWidget' );
        }
    };

    /*
     * Default plugin options
     */
    $.fn.formGenerator.defaultOptions = {
        mainAlertElement: $('#alerts-main')
    };

})( jQuery );
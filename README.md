# Form Generator Sprinkle for UserFrosting 4
This Sprinkle provides helper classes, Twig template and JavaScript plugins to generate HTML forms, modals and confirm modal bases on UserFrosting/[validation schemas](https://learn.userfrosting.com/routes-and-controllers/client-input/validation).

> This version only works with UserFrosting 4.1.x !

## Install
Edit UserFrosting `app/sprinkles.json` file and add the following to the `require` list : `"lcharette/uf_formgenerator": "~2.0.0"`. Also add `FormGenerator` to the `base` list. For example:

```
{
    "require": {
        "lcharette/uf_formgenerator": "~2.0.0"
    },
    "base": [
        "core",
        "account",
        "admin",
        "FormGenerator"
    ]
}
```

Run `composer update` then `php bakery bake` to install the sprinkle.

# Features and usage
Before starting with UfFormGenerator, you should read the main UserFrosting guide to familiarise yourself with _validation schemas_: (https://learn.userfrosting.com/routes-and-controllers/client-input/validation).

## Form generation
### Defining the fields in the schema
This sprinkle uses the `schemas` used by UserFrosting to validate form data to actually build form. To achieve this, a new `form` key is simply added to the fields found in a `schema` file. Let's skip the boring text and dive into an example.

Here's a simple `schema` used to validate a form used to create a `project`. The form will contain a `name`, `description` and `status` fields.

```
{
    "name" : {
        "validators" : {
            "length" : {
                "min" : 1,
                "max" : 100
            },
            "required" : {
                "message" : "PROJECT.VALIDATE.REQUIRED_NAME"
            }
        }
    },
    "description" : {
        "validators" : {}
    },
    "status" : {
        "validators" : {
            "member_of" : {
                "values" : [
                    "0", "1"
                ]
            },
            "required" : {
                "message" : "PROJECT.VALIDATE.STATUS"
            }
        }
    }
}
```
> Note: FormGenerator works with json and Yaml schemas.

At this point, with typical UserFrosting (or any framework) setup, you would be diving into your controller and twig files to manually create your HTML form. This can be easy if you have a handful of fields, but can be a pain with a dozen fields and more.

This is where FormGenerator steps in with the use of a new `form` attribute. Let's add it to our `project` form :

```
{
    "name" : {
        "validators" : {
            "length" : {
                "min" : 1,
                "max" : 100
            },
            "required" : {
                "message" : "VALIDATE.REQUIRED_NAME"
            }
        },
        "form" : {
            "type" : "text",
            "label" : "NAME",
            "icon" : "fa-flag",
            "placeholder" : "NAME"
        }
    },
    "description" : {
        "validators" : {},
        "form" : {
            "type" : "textarea",
            "label" : "DESCRIPTION",
            "icon" : "fa-pencil",
            "placeholder" : "DESCRIPTION",
            "rows" : 5
        }
    },
    "status" : {
        "validators" : {
            "member_of" : {
                "values" : [
                    "0", "1"
                ]
            },
            "required" : {
                "message" : "VALIDATE.STATUS"
            }
        },
        "form" : {
            "type" : "select",
            "label" : "STATUS",
            "options" : {
                "0" : "Active",
                "1" : "Disabled"
            }
        }
    }
}
```

Let's look closer at the `name` field :

```
"form" : {
    "type" : "text",
    "label" : "PROJECT.NAME",
    "icon" : "fa-flag",
    "placeholder" : "PROJECT.NAME"
}
```

Here you can see that we define the `type`, `label`, `icon` and `placeholder` value for this `name` field. You can define any standard [form attributes](http://www.w3schools.com/html/html_form_attributes.asp) plus the `icon` and `label`.

Currently, the following form elements are available:
- input
- textarea
- select
- bool (checkbox)
- number
- hidden

For the `select` element, a special `options` attribute containing an array of `key : value` can be used to define the

Of course, all strings can be defined using _translation keys_.

### The controller part
Once your fields defined in the `schema` json file, you need to load that schema in your controller.

First thing to do is add FormGenerator's `Form` class name space to your "use" list :
`use UserFrosting\Sprinkle\FormGenerator\Form;`

Next, where you load the schema and setup the `validator`, you simply add the new Form creation:
```
// Load validator rules
$schema = new RequestSchema("schema://project.json");
$validator = new JqueryValidationAdapter($schema, $this->ci->translator);

// Create the form
$form = new Form($schema, $project);
```

In this example, `$project` can contain the default (or current value) of the fields. A data collection fetched from the database with eloquent can also be passed directly. That second argument can also be omited to create an empty form.

Last thing to do is send the fields to Twig. In the list of retuned variables to the template, add the `fields` variable:
```
$this->ci->view->render($response, "pages/myPage.html.twig", [
    "fields" => $form->generate(),
    "validators" => $validator->rules('json', true)
]);

```

### The Twig template part

Now it's time to display the form in `myPage.html.twig` !

```
<form name="MyForm" method="post" action="/Path/to/Controller/Handling/Form">
    {% include "forms/csrf.html.twig" %}
    <div id="form-alerts"></div>
    <div class="row">
    	<div class="col-sm-8">
		    {% include 'FormGenerator/FormGenerator.html.twig' %}
    	</div>
    </div>
    <div class="row">
      <button type="submit" class="btn btn-block btn-lg btn-success">Submit</button>
    </div>
</form>
```

That's it! No need to list all the field manually. The ones defined in the `fields` variable will be displayed by `FormGenerator/FormGenerator.html.twig`. Note that this will only load the fields, not the form itself. The `<form>` tag and `submit` button needs to be added manually.

## Modal form
What if you want to show a form in a modal window? Well, it's even easier! It's basically three steps:
1. Setup your form schema (described above)
2. Setup the form in your controller
3. Call the modal from your template

## Setup the form in your controller
With your schema in hand, it's time to create a controller and route to load your modal. The controller code will be like the above with one exception: the `render` part. Example time!

```
$this->ci->view->render($response, "FormGenerator/modal.html.twig", [
    "box_id" => $get['box_id'],
    "box_title" => "PROJECT.CREATE",
    "submit_button" => "CREATE",
    "form_action" => '/project/create',
    "fields" => $form->generate(),
    "validators" => $validator->rules('json', true)
]);
```

As you can see, instead of rendering your own Twig template, you simply have to specify FormGenerator's modal template. This template requires the following variables:
1. `box_id`: This should always be `$get['box_id']`. This is used by the JavaScript code to actually display the modal.
2. `box_title`: The title of the modal.
3. `submit_button`: The label of the submit button. Optional. Default to `SUBMIT` (localized).
4. `form_action`: The route where the form will be sent
5. `fields`: The fields. Should always be `$form->generate()`
6. `validators`: Client side validators

## Call the modal from your template
So at this point you have a controller that displays the modal at a `/path/to/controller` route. Time to show that modal. Again, two steps:

First, define a link or a button that will call the modal when clicked:
```
<button class="btn btn-success js-displayForm" data-toggle="modal" data-formUrl="/path/to/controller">Create</button>
```

The important part here is the `data-formUrl` attribute. This is the route that will load your form. `js-displayForm` is used here to bind the button to the action.

Second, load the JavaScript. Add this to your Twig file:
```
{% block scripts_page %}

    <!-- Include page-specific JS -->
    {{ assets.js('js/FormGenerator') | raw }}

    <script>
        $(".js-displayForm").formGenerator();
    </script>
{% endblock %}
```

The `js-displayForm` class is used to apply the `formGenerator` plugin to all button containing the `js-displayForm` class.

## Modal confirmation
One side features of FormGenerator is the ability to add a confirmation modal to your pages with simple HTML5 attributes.

Let's look at a delete button / confirmation for our `project` :
```
<a href="#" class="btn btn-danger js-displayConfirm"
  data-confirm-title="Delete project ?"
  data-confirm-message="Are you sure you want to delete this project?"
  data-confirm-button="Yes, delete project"
  data-post-url="/porject/delete"
data-toggle="modal"><i class="fa fa-trash-o"></i> Delete</a>
```
(Note that content of data attributes can be translation keys)

Now simply add the JavaScript function and add it to your button, using the `js-displayConfirm` class:
```
{% block scripts_page %}

    <!-- Include page-specific JS -->
    {{ assets.js('js/FormGenerator') | raw }}

    <script>
        $(".js-displayConfirm").formGenerator('confirm');
    </script>
{% endblock %}
```

# Working example
See the [UF_FormGeneratorExample](https://github.com/lcharette/UF_FormGeneratorExample) repo for an example of the FormGenerator full code.

# Licence
By [Louis Charette](https://github.com/lcharette). Copyright (c) 2017, free to use in personal and commercial software as per the MIT license.

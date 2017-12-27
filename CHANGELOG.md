# Change Log

##2.2.8
- Fix icon in textarea macro

## 2.2.7
- Added `modal-large` template file.

## 2.2.6
- Fix issue with `binary` checkbox tests.
- Fix Text input style when no icon is added

## 2.2.5
- Added `binary` option for checkbox to disable UF binary checkbox system (bool; default true).

## 2.2.4
- Add necessary HTML to disable submit and cancel button in modal form.

## 2.2.3
- New `$form->setOptions` function to set options of a select element. Shortcut for using `setInputArgument` and `setValue`.

## 2.2.2
- Fix issue with error alert no displaying on confirmation dialog

## 2.2.1
- Initialize ufAlert if not already done
- Autofocus first form field when modal is displayed

## 2.2.0
- Refactored the javascript plugin
- Added new events
- Added new `redirectAfterSuccess` boolean option

## 2.1.2
- Fix warning with select macro

## 2.1.1
- Fix issue with the select macro
- Renamed macro templates with the `*.html.twig` extension

## 2.1.0
- Completely refactored how form fields are parsed, including how default value are defined. Each input type now defines it's own class for defining default values and transforming some input.
- Twig templates updated to reflect the new parser.
- Twig macros changed from `*.generate(name, value)` to `*.generate(input)`.
- **`Bool` type changed to `checkbox`**.
- Removed the `number` Twig template (Will use the text input one).
- Added unit tests.
- Support for any attributes in the schema. For example, if you need to add a data attribute to a field, your schema would be:
```
"myField" : {
    "form" : {
        "type" : "text",
        "label" : "My Field",
        "icon" : "fa-pencil",
        "data-something" : "blah"
    }
}
```

## 2.0.0
- Updated for UserFrosting v4.1.x

The custom `RequestSchema` have been removed. Instead of building the form directly on the schema using `$schema->initForm()`, you now create a new Form using `$form = new Form($schema)` and go on from there. Handling complex schema can now be done using the new loader system from UF 4.1.

`$schema->generateForm();` has also been changed to `$form->generate();`.

## 1.0.1
- Bug fixes

## 1.0.0
- Initial release

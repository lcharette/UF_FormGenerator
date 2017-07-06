# Change Log

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

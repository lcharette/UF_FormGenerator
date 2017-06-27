# Change Log

## 2.0.0
Updated for UserFrosting v4.1.x

The custom `RequestSchema` have been removed. Instead of building the form directly on the schema using `$schema->initForm()`, you now create a new Form using `$form = new Form($schema)` and go on from there. Handling complex schema can now be done using the new loader system from UF 4.1.

`$schema->generateForm();` has also been changed to `$form->generate();`.

## 1.0.1
Bug fixes

## 1.0.0
Initial release

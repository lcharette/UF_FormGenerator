# Form Generator Sprinkle
This Sprinkle provides helper classes, Twig template and javascript plugins to generate HTML forms bases on Userfrosting/Fortress `schemas`.

## Install
`cd` into the sprinkle directory of UserFrosting and clone as submodule:
```
git submodule add git@github.com:lcharette/UF_FormGenerator.git FormGenerator
```

Edit UserFrosting `public/index.php` file and add `FormGenerator` to the sprinkle list.

## Add the js bundle
Edit the `build/bundle.config.json` and add this at the end
```
"js/FormGenerator": {
    "scripts": [
        "vendor/bootstrap3-typeahead/bootstrap3-typeahead.min.js",
        "js/widget-formGenerator.js"
    ],
    "options": {
        "result": {
            "type": {
              "styles": "plain"
            }
        }
    }
}
```
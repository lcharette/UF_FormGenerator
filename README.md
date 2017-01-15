# Form Generator Sprinkle
This Sprinkle provides helper classes, Twig template and javascript plugins to generate HTML forms bases on Userfrosting/Fortress `schemas`.

## Install
`cd` into the sprinkle directory of UserFrosting and clone as submodule:
```
git submodule add git@github.com:lcharette/UF_FormGenerator.git FormGenerator
```

### Add to the sprinkle list
Edit UserFrosting `app/sprinkles/sprinkles.json` file and add `ConfigManager` to the sprinkle list to enable it globally.

### Update the assets build
From the UserFrosting `/build` folder, run `npm run uf-assets-install`
# Ckeditor component
Install js library
```bash
yarn add ckeditor4
```

Add entry on `webpack.config.js` :
```javascripts
Encore
    .addEntry('ckeditor', './vendor/umbrella2/corebundle/assets/ckeditor/ckeditor.js')
```

Rebuild javascripts with yarn and copy vendor on public/ directory
```bash
yarn build
cp -R node_modules/ckeditor4 public
```

You can now use `CkeditorType`on your symfony form :)
# Installing Codein eZ Platform SEO Toolkit

## Installation Steps

# Configure Webpack to build bundle assets

In your `webpack.config.js` (same level as your composer.json) :
```js
const codeinSeoToolkitConfig = require('./vendor/codein/ezplatform-seo-toolkit/bundle/Resources/encore/codein.config.js')(Encore);

module.exports = [ eZConfig, ...customConfigs, codeinSeoToolkitConfig ];
```

In your `config.yml`:
```yml
webpack_encore:
    builds:
        codein: "%kernel.project_dir%/web/bundles/codein-ezplatformseotoolkit"
```
# Installing Codein eZ Platform SEO Toolkit

## Installation Steps

# Add webpack entry

In your `webpack.config.js` (same level as your composer.json) :
```js
const codeinSeoToolkitConfig = require('./vendor/codein/ezplatform-seo-toolkit/bundle/Resources/encore/codein.config.js')(Encore);

module.exports = [ eZConfig, ...customConfigs, codeinSeoToolkitConfig ];
```
const path = require('path');
const fs = require('fs');
const translationsPath = path.resolve('./web/assets/translations/');

let jsEntries = [
  path.resolve(__dirname, "../public/js/modules/seo-menu/index.js"),
]

fs.readdirSync(translationsPath).forEach((file) => {
  if (file !== 'config.js' && path.extname(file) === '.js') {
    jsEntries.push(path.resolve(translationsPath, file));
  }
});

module.exports = (Encore) => {
  Encore.addEntry("codein-ezplatform-seo-toolkit-js", jsEntries);
};
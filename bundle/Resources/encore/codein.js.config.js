const path = require('path');
const fs = require('fs');

const translationsPath = path.resolve('./web/assets/translations/');

module.exports = (Encore) => {
    Encore.addEntry('codein-ezplatform-seo-toolkit-js', [
        path.resolve(__dirname, '../public/js/index.js')
    ])
}
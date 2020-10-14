const path = require('path');

module.exports = (Encore) => {
    Encore.addEntry('codein-ezplatform-seo-toolkit-css', [
        path.resolve(__dirname, '../public/scss/index.scss')
    ])
}
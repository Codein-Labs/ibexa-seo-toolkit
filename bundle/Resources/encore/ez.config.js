const path = require('path');
const addJSEntries = require('./codein.js.config.js');
const addCSSEntries = require('./codein.css.config.js');

module.exports = (Encore) => {
    addJSEntries(Encore);
    addCSSEntries(Encore);
};

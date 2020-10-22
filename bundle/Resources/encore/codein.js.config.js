const path = require("path");

module.exports = (Encore) => {
  Encore.addEntry("codein-ezplatform-seo-toolkit-js", [
    path.resolve(__dirname, "../public/js/modules/seo-menu/index.js"),
  ]);
};

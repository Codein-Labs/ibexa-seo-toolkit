const path = require("path");

module.exports = (Encore) => {
  Encore.addEntry("codein-ezplatform-seo-toolkit-css", [
    path.resolve(__dirname, "../public/scss/seo-menu.module.scss"),
    path.resolve(__dirname, "../public/scss/seo-loading.scss"),
    path.resolve(__dirname, "../public/scss/seo-analysis.scss"),
  ]);
};

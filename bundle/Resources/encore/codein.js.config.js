const path = require("path");
const fs = require("fs");

module.exports = (Encore) => {
  Encore.addEntry("codein-ezplatform-seo-toolkit-js", [
    path.resolve(__dirname, "../public/js/index.js"),
  ]);
};

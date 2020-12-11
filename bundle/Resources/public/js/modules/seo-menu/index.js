import React from "react";
import ReactDOM from "react-dom";
import App from "./app.js";

const ROOT_ELEMENT = "codein-seo-toolkit-root";

const root = document.getElementById(ROOT_ELEMENT);

const attr = {
  "contentId": Number(root.getAttribute('data-content-id')),
  "versionNo": Number(root.getAttribute('data-version-no')),
  "locationId": Number(root.getAttribute('data-location-id')),
  "contentTypeIdentifier": root.getAttribute('data-content-type-identifier'),
  "languageCode": root.getAttribute('data-language'),
  "siteaccesses": root.getAttribute('data-siteaccesses'),
}

ReactDOM.render(
  <React.StrictMode>
    <App contentAttributes={attr} />
  </React.StrictMode>,
  document.getElementById(ROOT_ELEMENT)
);

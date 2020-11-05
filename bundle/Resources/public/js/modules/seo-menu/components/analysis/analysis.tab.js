import React from "react";
import { __ } from "../../../../commons/services/language.service";
import AnalysisCategoryContent from "./analysis.category.content";

const AnalysisTab = (props) => {
  const transAccordionTitleKeyword = __(
    "codein_seo_toolkit.seo_view.tab_analysis_accordion_title_keyword"
  );

  const transAccordionTitleReadability = __(
    "codein_seo_toolkit.seo_view.tab_analysis_accordion_title_readability"
  );

  return (
    <>
      <div class="accordion" id="accordionCategory">
        <div class="ez-view-rawcontentview">
          <div class="ez-raw-content-title d-flex justify-content-between mb-3" id="headingOne">
            <h2 class="mb-0">
              <a
                class="ez-content-preview-toggle"
                type="button"
                data-toggle="collapse"
                data-target="#collapseOne"
                aria-expanded="true"
                aria-controls="collapseOne"
              >
                {transAccordionTitleKeyword}
              </a>
            </h2>
          </div>

          <div
            id="collapseOne"
            class="ez-content-preview-collapse collapse show"
            aria-labelledby="headingOne"
            data-parent="#accordionCategory"
          >
            <div class="card-body">
              <AnalysisCategoryContent />
            </div>
          </div>
        </div>
        <div class="ez-view-rawcontentview">
          <div class="ez-raw-content-title d-flex justify-content-between mb-3" id="headingTwo">
            <h2 class="mb-0">
              <a
                class="ez-content-preview-toggle collapsed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseTwo"
                aria-expanded="false"
                aria-controls="collapseTwo"
              >
                {transAccordionTitleReadability}
              </a>
            </h2>
          </div>
          <div
            id="collapseTwo"
            class="ez-content-preview-collapse collapse"
            aria-labelledby="headingTwo"
            data-parent="#accordionCategory"
          >
            <div class="card-body">
              <AnalysisCategoryContent />
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AnalysisTab;

import React from "react";
import { __ } from "../../../../commons/services/language.service";
import AnalysisKeyword from "./analysis.keyword";
import AnalysisReadability from "./analysis.readability";

const AnalysisTab = (props) => {
  const transAccordionTitleKeyword = __(
    "codein_seo_toolkit.seo_view.tab_analysis_accordion_title_keyword"
  );

  const transAccordionTitleReadability = __(
    "codein_seo_toolkit.seo_view.tab_analysis_accordion_title_readability"
  );

  return (
    <>
      <div class="accordion" id="accordionExample">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h2 class="mb-0">
              <button
                class="btn btn-link btn-block text-left"
                type="button"
                data-toggle="collapse"
                data-target="#collapseOne"
                aria-expanded="true"
                aria-controls="collapseOne"
              >
                {transAccordionTitleKeyword}
              </button>
            </h2>
          </div>

          <div
            id="collapseOne"
            class="collapse show"
            aria-labelledby="headingOne"
            data-parent="#accordionExample"
          >
            <div class="card-body">
              <AnalysisKeyword />
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
              <button
                class="btn btn-link btn-block text-left collapsed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseTwo"
                aria-expanded="false"
                aria-controls="collapseTwo"
              >
                {transAccordionTitleReadability}
              </button>
            </h2>
          </div>
          <div
            id="collapseTwo"
            class="collapse"
            aria-labelledby="headingTwo"
            data-parent="#accordionExample"
          >
            <div class="card-body">
              <AnalysisReadability />
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AnalysisTab;

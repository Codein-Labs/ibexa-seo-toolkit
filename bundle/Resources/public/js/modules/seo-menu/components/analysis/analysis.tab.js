import React from "react";
import { __ } from "../../../../commons/services/language.service";
import AnalysisCategoryContent from "./analysis.category.content";
import { getAnalysis, getSeoRichText } from './analysis.service';
import { validateContextData } from '../../services/validator.helper';
import EzDataContext from "../../ez.datacontext";



const SELECTOR_FIELD = '.ez-field-edit--ezrichtext';
const SELECTOR_INPUT = '.ez-data-source__richtext';

var toType = function(obj) {
  return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
}


export default class AnalysisTab extends React.Component {

  constructor(props) {
    super(props);
    this.seoRichText = "";
    this.seoData = [];
    this.triggerAnalysis = this.triggerAnalysis.bind(this);
  }
  
  
  
  triggerAnalysis(e) {
    e.preventDefault();
    if (!validateContextData(this.context)) return;

    var self = this;
    getAnalysis(this.context.contentId, getSeoRichText(), (err, res) => {
      if (!err) {
        self.seoData = res;
        console.log(self.seoData);
      }
      return;
    })
  }

  
  render() {
    const transAccordionTitleKeyword = __(
      "codein_seo_toolkit.seo_view.tab_analysis_accordion_title_keyword"
    );
    
    const transAccordionTitleReadability = __(
      "codein_seo_toolkit.seo_view.tab_analysis_accordion_title_readability"
    );
    
    const transAnalyzeButton = __(
      "codein_seo_toolkit.seo_view.tab_analysis.triggerAnalysis"
    );
    return (
      <>
        <div class="accordion" id="accordionCategory">
          {/* {this.seoData?.map((seoAnalysisCategoryValue, seoAnalysisCategoryName) => (
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
                    {__("codein_seo_toolkit.seo_view.tab_analysis_accordion_title_" + seoAnalysisCategoryName)}
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
                  <AnalysisCategoryContent content={seoAnalysisCategoryValue}/>
                </div>
              </div>
            </div>
          ))} */}
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
  
        <hr class="separator mt-2"></hr>
        <button class="btn btn-primary" onClick={this.triggerAnalysis}>{transAnalyzeButton}</button>
      </>
    );
  }
}

AnalysisTab.contextType = EzDataContext;
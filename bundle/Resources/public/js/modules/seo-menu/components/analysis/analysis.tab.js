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
    this.state = {
      'selectedSiteaccess': '',
      'fetchCount': 0
    }
    this.siteaccesses = [];
    this.seoRichText = "";
    this.seoData = {};
    this.triggerAnalysis = this.triggerAnalysis.bind(this);
    this.handleSiteAccessChange = this.handleSiteAccessChange.bind(this);
  }
  
  componentDidMount() {
    if (this.context.siteaccesses == "") return;

    let siteaccesses = JSON.parse(this.context.siteaccesses);
    if (0 in siteaccesses) {
      this.setState({ 'selectedSiteaccess': siteaccesses[0] });
    }
    this.siteaccesses = siteaccesses;
  }
  
  triggerAnalysis(e) {
    e.preventDefault();

    var self = this;

    let dataContext = this.context;
    delete dataContext.siteaccesses;
    dataContext.siteaccess = this.state.selectedSiteaccess;


    if (!validateContextData(dataContext)) return;
    getAnalysis(dataContext, getSeoRichText(), (err, res) => {
      if (!err) {
        self.seoData = res;
        self.forceUpdate()
        console.log(self.seoData);
      }
      else {
        console.error(err);
      }
      return;
    })
  }

  handleSiteAccessChange(event) {
    this.setState({selectedSiteaccess: event.target.value});
  }

  
  render() {
    const transAccordionTitleKeyword = __("codein_seo_toolkit.seo_view.tab_analysis.accordion_title_keyword");
    const transAccordionTitleReadability = __("codein_seo_toolkit.seo_view.tab_analysis.accordion_title_readability");
    const transAnalyzeButton = __("codein_seo_toolkit.seo_view.tab_analysis.triggerAnalysis");
    const transTipsSaving = __('codein_seo_toolkit.seo_view.tab_analysis.tips.saving');
    const transTipsTraffic = __('codein_seo_toolkit.seo_view.tab_analysis.tips.traffic');
    const transTipsTrafficGreen = __('codein_seo_toolkit.seo_view.tab_analysis.tips.traffic.green');
    const transTipsTrafficOrange = __('codein_seo_toolkit.seo_view.tab_analysis.tips.traffic.orange');
    const transTipsTrafficRed = __('codein_seo_toolkit.seo_view.tab_analysis.tips.traffic.red');
    const transTipsDeveloper = __('codein_seo_toolkit.seo_view.tab_analysis.tips.developer_support');

    return (
      <>
        <a className="badge badge-info collapsed" data-toggle="collapse" href="#systemInfoCollapse" aria-expanded="false">⚠ Tips</a>
        <div className="collapse" id="systemInfoCollapse">
          <div className="alert alert-info mb-0 mt-3" role="alert">
            <ul>
              <li>{transTipsSaving}</li>
              <li>
                {transTipsTraffic}
                <ul>
                  <li>{transTipsTrafficGreen}</li>
                  <li>{transTipsTrafficOrange}</li>
                  <li>{transTipsTrafficRed}</li>
                </ul>
              </li>
              <li>{transTipsDeveloper}</li>
            </ul>
          </div>
        </div>
        <div class="ez-field-edit__label-wrapper">
          <label class="ez-field-edit__label" for="ezrepoforms_content_edit_fieldsData_new_type_value">Siteaccess analyzed:</label>
        </div>
        <select id="siteaccess-selection" class="analysis-content__siteaccess-selection form-control" value={this.state.selectedSiteaccess} onChange={this.handleSiteAccessChange}>
          {this.siteaccesses?.map((siteaccess, index) => (
            <option value={siteaccess}>{siteaccess}</option>
          ))}
        </select>
        <div class="accordion" id="accordionCategory">
        
          {Object.keys(this.seoData)?.map((seoAnalysisCategoryName) => (
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
                    {__(seoAnalysisCategoryName)}
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
                  <AnalysisCategoryContent content={this.seoData[seoAnalysisCategoryName]}/>
                </div>
              </div>
            </div>
          ))}
          {/* <div className="ez-view-rawcontentview">
            
            <div className="ez-raw-content-title d-flex justify-content-between mb-3" id="headingOne">
              <h2 className="mb-0">
                <a
                  className="ez-content-preview-toggle"
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
          </div> */}
        </div>
  
        <hr class="separator mt-2"></hr>
        <button class="btn btn-primary" onClick={this.triggerAnalysis}>{transAnalyzeButton}</button>
      </>
    );
  }
}

AnalysisTab.contextType = EzDataContext;
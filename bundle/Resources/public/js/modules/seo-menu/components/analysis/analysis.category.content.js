import React from "react";
import { __ } from "../../../../commons/services/language.service";


export default class AnalysisCategoryContent extends React.Component {

  constructor(props) {
    super(props);
    this.renderAnalysis = this.renderAnalysis.bind(this);
  }

  renderAnalysis() {
    return Object.keys(this.props.content)?.map(analyzer => {
      const TRANS_READABLE_NAME = __("codein_seo_toolkit.analyzer." + analyzer + ".readable_name", "codein_seo_toolkit");
      const TRANS_INFO_STRING = "codein_seo_toolkit.analyzer." + analyzer + ".info";
      const TRANS_INFO = __(TRANS_INFO_STRING);
      if (this.props.content[analyzer].status) {
        return (
        <>
          <p className={"analysis-content__result analysis-content__result--" + this.props.content[analyzer].status}>
            {__("codein_seo_toolkit.analyzer."+ analyzer +".message." + this.props.content[analyzer].status, "codein_seo_toolkit", this.props.content[analyzer].data)}
            {TRANS_INFO != TRANS_INFO_STRING ? (
              <a className="ml-3 badge badge-dark collapsed" data-toggle="collapse" href={'#'+analyzer+'Tooltip'} aria-expanded="false" role="button">?</a>
            ) : ''}
          </p>
          <div className="collapse" id={analyzer+'Tooltip'}>
              {TRANS_INFO != TRANS_INFO_STRING ? (
                <div className="alert alert-dark mb-3 mt-0" role="alert">
                  {TRANS_INFO}
                </div>
              ) : ''}
          </div>
        </>
        );
      }
      else {
        return (
          <p className="analysis-content__result analysis-content__result--not-compatible">
            {__("codein_seo_toolkit.analyzer.not_compatible", "codein_seo_toolkit", { 'analyzer': TRANS_READABLE_NAME } )} 
          </p>
        );
      }
    })
  }
 
  render() {
    return (
      <>
        <div className="analysis-content">
          {this.renderAnalysis()}
        </div>
      </>
    );
  }
}
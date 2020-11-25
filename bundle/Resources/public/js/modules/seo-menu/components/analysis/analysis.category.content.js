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
      if (this.props.content[analyzer].status) {
        return (
          <p className={"analysis-content__result analysis-content__result--" + this.props.content[analyzer].status}>
            {__("codein_seo_toolkit.analyzer."+ analyzer +".message." + this.props.content[analyzer].status, "codein_seo_toolkit", this.props.content[analyzer].data)}
          </p>
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
// const AnalysisCategoryContent = (props) => {
//   return (
//     <>
//       <div className="analysis-content">
//       {Object.keys(props.content)?.map(analyzer => (
//         <p className={"analysis-content__result analysis-content__result--" + props.content[analyzer].status}>
//           {__("codein_seo_toolkit.analyzer."+ analyzer +".message." + props.content[analyzer].status, "codein_seo_toolkit", props.content[analyzer].data)}
//         </p>
//       ))}
//       </div>
//     </>
//   );
// };

// export default AnalysisCategoryContent;

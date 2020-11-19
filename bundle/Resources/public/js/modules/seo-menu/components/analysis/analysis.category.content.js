import React from "react";
import { __ } from "../../../../commons/services/language.service";

const AnalysisCategoryContent = (props) => {
  return (
    <>
      <div className="analysis-content">
      {Object.keys(props.content)?.map(analyzer => (
        <p className={"analysis-content__result analysis-content__result--" + props.content[analyzer].status}>
          {__("codein_seo_toolkit.analyzer."+ analyzer +".message." + props.content[analyzer].status, "codein_seo_toolkit", props.content[analyzer].data)}
        </p>
      ))}
      </div>
    </>
  );
};

export default AnalysisCategoryContent;

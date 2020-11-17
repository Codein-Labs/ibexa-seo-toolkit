import React from "react";

const AnalysisCategoryContent = (props) => {
  return (
    <>
      <div className="analysis-content">
        <p className="analysis-content__result analysis-content__result--high">
          Texte d'analyse high score
        </p>
        <p className="analysis-content__result analysis-content__result--medium">
          Texte d'analyse medium score
        </p>
        <p className="analysis-content__result analysis-content__result--low">
          Texte d'analyse low score
        </p>
      </div>
    </>
  );
};

export default AnalysisCategoryContent;

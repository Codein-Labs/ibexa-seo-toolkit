import React from "react";
import Logo from "../../../../img/SEO-Toolkit_logo.svg";
import { __ } from "../../../commons/services/language.service";
import AnalysisTab from "./analysis/analysis.tab";
import ConfigurationTab from "./configuration/configuration.tab";
import SeoViewTabs from "./seo_view.tabs";
import SeoViewTabsNav from "./seo_view.tabs.nav";

export default class SeoView extends React.Component {
  constructor(props) {
    super(props);
    this.props = props;
    this.state = {
      currentTabIndex: 0,
    };
    this.selectTab = this.selectTab.bind(this);
  }

  selectTab(index) {
    this.setState({ currentTabIndex: index });
  }

  render() {
    const transTitle = __("codein_seo_toolkit.seo_view.title");
    const transDesc = __("codein_seo_toolkit.seo_view.desc");
    const transMadeWith = __("codein_seo_toolkit.seo_view.footer_made_with");
    const transMadeBy = __("codein_seo_toolkit.seo_view.footer_made_by");

    const transTabAnalysis = __("codein_seo_toolkit.seo_view.tab_analysis");
    const transTabConfiguration = __(
      "codein_seo_toolkit.seo_view.tab_configuration"
    );

    const TABS = [
      { title: transTabAnalysis, component: <AnalysisTab /> },
      { title: transTabConfiguration, component: <ConfigurationTab /> },
    ];
    return (
      <>
        <div className="ez-header pt-2">
          <div className="container px-0 pb-4 pt-3 ez-content-edit-container">
            <a
              class="ez-content-edit-container__close"
              href="#"
              onClick={this.props.closeMenu}
            ></a>
          </div>
        </div>
        <div className="px-0 pb-4 ez-content-container">
          <div className="page-inner">
            <div className="ez-header">
              <div className="container">
                <div class="ez-page-title py-3">
                  <h1 class="ez-page-title__content-item">
                    <img src={Logo} alt="" className="ez-icon" />
                    <div class="ez-page-title__content-name" title="Home">
                      {transTitle}
                    </div>
                  </h1>
                  <h4 class="ez-page-title__content-type-name">{this.props.contentName}</h4>
                </div>
              </div>
            </div>
            <div className="tab-content container mt-4 pb-3">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <div class="tab-content" id="nav-tabContent">
                    <SeoViewTabs
                      tabs={TABS}
                      selectTab={this.selectTab}
                      currentTabIndex={this.state.currentTabIndex}
                    />
                  </div>
                </div>
              </div>
            </div>
            <div className="container pb-4">
              <p>
                {transMadeWith} ❤️ {transMadeBy}{" "}
                <a href="https://codein.fr">Codéin</a>.
              </p>
            </div>
          </div>
        </div>
      </>
    );
  }
}
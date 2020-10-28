import React from "react";
import Logo from "../../../../img/SEO-Toolkit_logo.svg";
import AnalysisIndex from "./analysis/analysis.index";
import SeoViewTabs from "./seo_view.tabs";
import SeoViewTabsNav from "./seo_view.tabs.nav";

const TABS = [
  { title: "Analyse SEO", component: <AnalysisIndex /> },
  { title: "Configuration SEO", component: <></> },
];

export default class SeoView extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      currentTabIndex: 0,
    };
    this.selectTab = this.selectTab.bind(this);
  }

  selectTab(index) {
    this.setState({ currentTabIndex: index });
  }

  render() {
    const transTitle = Translator.trans(
      "codein_seo_toolkit.seo_view.title",
      {},
      "codein_seo_toolkit"
    );

    const transDesc = Translator.trans(
      "codein_seo_toolkit.seo_view.desc",
      {},
      "codein_seo_toolkit"
    );

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
                  <h4 class="ez-page-title__content-type-name">{transDesc}</h4>
                </div>
                <SeoViewTabsNav
                  tabs={TABS}
                  selectTab={this.selectTab}
                  currentTabIndex={this.state.currentTabIndex}
                />
              </div>
            </div>
            <div className="tab-content container mt-4">
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
            <div className="container">
              <p>
                Fait avec ❤️ par <a href="https://codein.Fr">Codéin</a>.
              </p>
            </div>
          </div>
        </div>
      </>
    );
  }
}

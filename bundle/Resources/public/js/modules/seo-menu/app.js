import react from "react";

import SeoView from "./components/seo_view";
import SeoViewNotConfigured from "./components/seo_view_not_configured";
import EzDataContext from "./ez.datacontext";

export default class App extends react.Component {
  constructor(props) {
    super(props);
    this.props = props;
    this.state = { seoMenuOpened: false, isConfigured: true };

    this.toggleSeoMenu = this.toggleSeoMenu.bind(this);
    this.onCloseMenu = this.onCloseMenu.bind(this);
  }

  componentDidMount() {
    window.addEventListener("load", this.toggleSeoMenu, false);
  }

  componentWillUnmount() {
    window.removeEventListener("load", this.toggleSeoMenu, false);
  }

  /**
   * Needed because right sidebar is NOT initilized on React"s loading.
   */
  toggleSeoMenu() {
    const seoButtonAnalysis = document.getElementById(
      "menu_item_seo_analyzer-tab"
    );

      // If analysis is not configured for the content type
      const seoButtonAnalysisNotConfigured = document.getElementById(
          "menu_item_seo_analyzer_not_configured-tab"
      );

    // Allow for the bundle to display properly on landing pages.
    const ezPageBuilderFields = document.querySelector(
      '.ez-page-builder__fields'
    );
    if(seoButtonAnalysis) {
        seoButtonAnalysis.addEventListener(
          "click",
          () => {
            this.setState((state) => ({
              seoMenuOpened: !state.seoMenuOpened,
              isConfigured: true
            }), function () {
              if (this.state.seoMenuOpened) {
                seoButtonAnalysis.classList.remove('btn-primary');
                seoButtonAnalysis.classList.add('btn-secondary');

                if (ezPageBuilderFields != null)
                {
                  ezPageBuilderFields.style.display = "block";
                }
              } else {
                seoButtonAnalysis.classList.remove('btn-secondary');
                seoButtonAnalysis.classList.add('btn-primary');

                if (ezPageBuilderFields != null && document.querySelector('.ez-page-builder-edit:not(.ez-page-builder--fields-visible).ez-page-builder__fields') !== null) {
                  ezPageBuilderFields.style.display = "none";

                }

              }
            });
          },
          false
        );
    }
      if (seoButtonAnalysisNotConfigured) {
          seoButtonAnalysisNotConfigured.addEventListener(
              "click",
              () => {
                  this.setState((state) => ({
                      seoMenuOpened: !state.seoMenuOpened,
                      isConfigured: false
                  }), function () {
                      if (this.state.seoMenuOpened) {
                          seoButtonAnalysisNotConfigured.classList.remove('btn-primary');
                          seoButtonAnalysisNotConfigured.classList.add('btn-secondary');

                          if (ezPageBuilderFields != null)
                          {
                              ezPageBuilderFields.style.display = "block";
                          }
                      }
                      else {
                          seoButtonAnalysisNotConfigured.classList.remove('btn-secondary');
                          seoButtonAnalysisNotConfigured.classList.add('btn-primary');
                      }
                  });
              }
          )
      }
  }

  onCloseMenu() {
    const seoButtonAnalysis = document.getElementById(
      "menu_item_seo_analyzer-tab"
    );

    const seoButtonAnalysisNotConfigured = document.getElementById(
        "menu_item_seo_analyzer_not_configured-tab"
    );

    this.setState(() => ({
      seoMenuOpened: false,
    }));

    if (seoButtonAnalysis) {
        seoButtonAnalysis.classList.remove('btn-secondary');
        seoButtonAnalysis.classList.add('btn-primary');
    }
    if (seoButtonAnalysisNotConfigured) {
        seoButtonAnalysisNotConfigured.classList.remove('btn-secondary');
        seoButtonAnalysisNotConfigured.classList.add('btn-primary');
    }
  }

  render() {
    if (this.state.seoMenuOpened) {
        if (this.state.isConfigured) {
            return(
                <div className="page" style={{ zIndex: 2, overflowY: "scroll", background: "#fafafa" }}>
                    <div>
                        <EzDataContext.Provider value={this.props.contentAttributes}>
                            <SeoView closeMenu={this.onCloseMenu} contentName={this.props.contentName} />
                        </EzDataContext.Provider>
                    </div>
                </div>
            );
        }
        else {
            return(
                <div className="page" style={{ zIndex: 2, overflowY: "scroll", background: "#fafafa" }}>
                    <div>
                        <EzDataContext.Provider value={this.props.contentAttributes}>
                            <SeoViewNotConfigured closeMenu={this.onCloseMenu} contentName={this.props.contentName} />
                        </EzDataContext.Provider>
                    </div>
                </div>
            );
        }
    } else {
      return (
        <>
        </>
      )
    }
  }
}

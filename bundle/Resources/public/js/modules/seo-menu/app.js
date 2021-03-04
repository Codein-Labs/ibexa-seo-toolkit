import react from "react";

import SeoView from "./components/seo_view";
import EzDataContext from "./ez.datacontext";

export default class App extends react.Component {
  constructor(props) {
    super(props);
    this.props = props;
    this.state = { seoMenuOpened: false };

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
  }

  onCloseMenu() {
    const seoButtonAnalysis = document.getElementById(
      "menu_item_seo_analyzer-tab"
    );
    this.setState(() => ({
      seoMenuOpened: false,
    }));
    seoButtonAnalysis.classList.remove('btn-secondary');
    seoButtonAnalysis.classList.add('btn-primary');
  }

  render() {
    if (this.state.seoMenuOpened) {
      return(
        <div className="page" style={{ zIndex: 2, overflowY: "scroll", background: "#fafafa" }}>
              <div>
                <EzDataContext.Provider value={this.props.contentAttributes}>
                  <SeoView closeMenu={this.onCloseMenu} contentName={this.props.contentName} />
                </EzDataContext.Provider>
              </div>
            </div>
      );
    } else {
      return (
        <>
        </>
      )
    }
  }
}

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
    if(seoButtonAnalysis) {
        seoButtonAnalysis.addEventListener(
          "click",
          () => {
            this.setState((state) => ({
              seoMenuOpened: !state.seoMenuOpened,
            }), function () {
              if (this.state.seoMenuOpened) {
                seoButtonAnalysis.classList.remove('btn-secondary');
                seoButtonAnalysis.classList.add('btn-primary');
              } else {
                seoButtonAnalysis.classList.remove('btn-primary');
                seoButtonAnalysis.classList.add('btn-secondary');
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
    seoButtonAnalysis.classList.remove('btn-primary');
    seoButtonAnalysis.classList.add('btn-secondary');
  }

  render() {
    if (this.state.seoMenuOpened) {
      return(
        <div className="page" style={{ zIndex: 2, overflowY: "scroll", background: "#fafafa" }}>
              <div>
                <EzDataContext.Provider value={this.props.contentAttributes}>
                  <SeoView closeMenu={this.onCloseMenu} />
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

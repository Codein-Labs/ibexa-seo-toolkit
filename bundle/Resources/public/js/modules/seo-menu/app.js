import React from "react";
import { animated, Transition } from "react-spring/renderprops";
import SeoView from "./components/seo_view";
import EzDataContext from "./ez.datacontext";

export default class App extends React.Component {
  constructor(props) {
    super(props);
    this.props = props;
    this.state = { seoMenuOpened: false };

    this.toggleSeoMenu = this.toggleSeoMenu.bind(this);
    this.onCloseMenu = this.onCloseMenu.bind(this);

    console.log(this.props.contentAttributes)
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

    seoButtonAnalysis.addEventListener(
      "click",
      () => {
        this.setState((state) => ({
          seoMenuOpened: !state.seoMenuOpened,
        }));
      },
      false
    );
  }

  onCloseMenu() {
    this.setState(() => ({
      seoMenuOpened: false,
    }));
  }

  render() {
    return (
      <Transition
        native
        reset
        unique
        items={this.state.seoMenuOpened}
        from={{ opacity: 0, transform: "translate3d(100%,0,0)" }}
        enter={{ opacity: 1, transform: "translate3d(0%,0,0)" }}
        leave={{ opacity: 0, transform: "translate3d(-50%,0,0)" }}
      >
        {(seoMenuOpened) => (style) =>
          !seoMenuOpened ? (
            <animated.div style={{ ...style }}></animated.div>
          ) : (
            <div className="page" style={{ zIndex: 2 }}>
              <animated.div style={{ ...style, background: "#fafafa" }}>
                <EzDataContext.Provider value={this.props.contentAttributes}>
                  <SeoView closeMenu={this.onCloseMenu} />
                </EzDataContext.Provider>
              </animated.div>
            </div>
          )}
      </Transition>
    );
  }
}

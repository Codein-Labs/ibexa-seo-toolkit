import React from "react";
import { animated, Transition } from "react-spring/renderprops";
import styled from "styled-components";
import SeoView from "./components/seo_view";

const Page = styled.div`
  position: fixed;
  width: 100%;
  height: 100vh;
  top: 0px;
  bottom: 0px;
  right: 0px;
  left: 0px;
  z-index: -1;
`;

export default class App extends React.Component {
  constructor(props) {
    super(props);

    this.state = { seoMenuOpened: false };

    this.toggleSeoMenu = this.toggleSeoMenu.bind(this);
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
            <Page style={{ zIndex: 2 }}>
              <animated.div style={{ ...style, background: "#15154b" }}>
                <SeoView />
              </animated.div>
            </Page>
          )}
      </Transition>
    );
  }
}

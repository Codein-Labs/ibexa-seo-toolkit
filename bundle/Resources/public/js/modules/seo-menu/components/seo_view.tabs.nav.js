import React, { Component } from "react";

export default class SeoViewTabsNav extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <>
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            {this.props.tabs?.map((tab, index) => (
              <a
                className={`nav-link ${
                  this.props.currentTabIndex === index ? " active" : ""
                }`}
                data-toggle="tab"
                href={"#nav-" + index}
                role="tab"
                aria-controls={"nav-" + index}
                aria-selected="true"
                onClick={() => {
                  this.props.selectTab(index);
                }}
              >
                {tab.title}
              </a>
            ))}
          </div>
        </nav>
      </>
    );
  }
}

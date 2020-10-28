import React, { Component } from "react";

export default class SeoViewTabs extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <>
        {this.props.tabs?.map((tab, index) => (
          <div
            className={`tab-pane fade show${
              this.props.currentTabIndex === index ? " active" : ""
            }`}
            id={"nav-" + index}
            role="tabpanel"
            aria-labelledby={"nav-" + index}
          >
            {this.props.currentTabIndex === index && <>{tab.component}</>}
          </div>
        ))}
      </>
    );
  }
}

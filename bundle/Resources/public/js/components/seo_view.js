import React, { PureComponent } from "react";
import styled from "styled-components";

const Page = styled.div`
  width: 100%;
  height: 100vh;
  z-index: 10;
`;

export default class SeoView extends PureComponent {
  render() {
    return (
      <>
        <Page></Page>
      </>
    );
  }
}

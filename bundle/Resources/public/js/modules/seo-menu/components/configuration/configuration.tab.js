
import React from "react";
import { __ } from "../../../../commons/services/language.service";
import { getConfiguration } from './configuration.service';
import EzDataContext from "../../ez.datacontext";
import { validateContextData } from '../../services/validator.helper';



const SELECTOR_FIELD = '.ez-field-edit--ezrichtext';
const SELECTOR_INPUT = '.ez-data-source__richtext';

export default class ConfigurationTab extends React.Component {

  constructor(props) {
    super(props);
    this.configuration = {
      'focusKeyword': '',
      'isPillarPage': false
    }


  }

  componentDidMount() {
    if (validateContextData(this.context)) {
      var self = this;
      getConfiguration(this.context.contentTypeIdentifier, function (err, res) {
        if (!err) {

        }
      });
    }
  }

  render() {

    const transConfigurationKeyword = __(
      "codein_seo_toolkit.seo_view.tab_configuration_keyword"
    );
    const transConfigurationIsPillar = __(
      "codein_seo_toolkit.seo_view.tab_configuration_is_pillar"
    );
    const transConfigurationUpdateConfiguration = __(
      "codein_seo_toolkit.seo_view.tab_configuration_update_configuration"
    );
    return (
      <>
        <form>
          <label>
            {}
            <input type="text" name="keyword"/>
          </label>
        </form>
      </>
    );
  }
}

ConfigurationTab.contextType = EzDataContext;

import React from "react";
import { __ } from "../../../../commons/services/language.service";
import { getConfiguration, updateConfiguration } from './configuration.service';
import EzDataContext from "../../ez.datacontext";
import { validateContextData } from '../../services/validator.helper';

const SELECTOR_FIELD = '.ez-field-edit--ezrichtext';
const SELECTOR_INPUT = '.ez-data-source__richtext';

export default class ConfigurationTab extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      'focusKeyword': '',
      'isPillarContent': false,
      'loading': true
    }
    this.triggerUpdateConfiguration = this.triggerUpdateConfiguration.bind(this);
    this.onChangeFocusKeyword = this.onChangeFocusKeyword.bind(this)
    this.onChangePillar = this.onChangePillar.bind(this)

  }

  componentDidMount() {
    if (validateContextData(this.context)) {
      var self = this;
      getConfiguration(this.context.contentId, function (err, res) {
        if (!err) {
          console.log(res);
          self.setState({
            focusKeyword: res.keyword ? res.keyword : '',
            isPillarContent: res.isPillarContent,
          })
        }
        else {
          console.log(err);
        }
        self.setState({
          loading: false
        })
      });
    }
  }

  triggerUpdateConfiguration() {
    this.setState({
      loading: true
    })
    if (validateContextData(this.context)) {
      var self = this;
      updateConfiguration(this.context.contentId, this.state.focusKeyword, this.state.isPillarContent, this.context.languageCode, function(err, res) {
        self.setState({
          loading: false
        })
      })
    }
  }

  onChangeFocusKeyword(event) {
    this.setState({focusKeyword: event.target.value})
  }
  
  onChangePillar(event) {
    console.log(event.target.checked)
    this.setState({isPillarContent: event.target.checked})
  }


  render() {
    let pillarCheckboxStyle = {
      'width': '30px'
    }
    const transConfigurationKeyword = __(
      "codein_seo_toolkit.seo_view.tab_configuration_keyword"
    );
    const transConfigurationIsPillar = __(
      "codein_seo_toolkit.seo_view.tab_configuration_is_pillar"
    );
    const transConfigurationUpdateConfiguration = __(
      "codein_seo_toolkit.seo_view.tab_configuration_update_configuration"
    );
    const transConfigurationKeywordSynonyms = __(
      "codein_seo_toolkit.seo_view.tab_configuration_keyword_synonyms"
    );
    const css = ``
    if (this.state.loading) {
      return (
        <>
          <style>
            {css}
          </style>

          <div className="d-flex justify-content-center">
            <div className="lds-dual-ring"></div>
          </div>
        </>
      )
    }

    return (
      <>
        <form>
          <div className="ez-field-edit">
            <div className="ez-field-edit__label-wrapper">
                <label className="ez-field-edit__label" for="keyword">{transConfigurationKeyword}</label>            
            </div>
            <div className="ez-field-edit__data">
                <div className="ez-data-source">
                  <input type="text" id="keyword" name="keyword" className="ez-data-source__input form-control" value={this.state.focusKeyword} onChange={this.onChangeFocusKeyword} />
                </div>
            </div>
          </div>
          <div className="ez-field-edit ez-field-edit--ezboolean">
            <div className="ez-field-edit__label-wrapper">
                <label className="ez-field-edit__label" for="isPillarContent">{transConfigurationIsPillar}</label>            
            </div>
            <div className="ez-field-edit__data">
              <div className="ez-data-source">
                <input type="checkbox" id="isPillarContent" name="isPillarContent" style={pillarCheckboxStyle} className="ez-data-source__input ez-data-source__input--pillar-content form-control" checked={this.state.isPillarContent} onChange={this.onChangePillar} />
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-primary" onClick={this.triggerUpdateConfiguration}>{transConfigurationUpdateConfiguration}</button>
          <hr/>
          <em class="light-text">{transConfigurationKeywordSynonyms}</em>
        </form>
      </>
    );
  }
}

ConfigurationTab.contextType = EzDataContext;
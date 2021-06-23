import React from "react";
import Logo from "../../../../img/SEO-Toolkit_logo.svg";
import { __ } from "../../../commons/services/language.service";

export default class SeoViewNotConfigured extends React.Component {
    render() {
        const transTitle = __("codein_seo_toolkit.seo_view.title");
        const transErrorNotConfigured = __("codein_seo_toolkit.seo_view.not_configured");
        return (
            <>
                <div className="ez-header codein-header pt-2">
                    <div className="container px-0 pb-4 pt-3 ez-content-edit-container">
                        <a
                            class="ez-content-edit-container__close"
                            href="#"
                            onClick={this.props.closeMenu}
                            data-original-title="Exit"
                        >
                            <svg class="ez-icon ez-icon--small ez-icon--primary">
                                <use xlinkHref="/bundles/ibexaplatformicons/img/all-icons.svg#discard"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div className="px-0 pb-4 ez-content-container">
                    <div className="page-inner">
                        <div className="ez-header">
                            <div className="container">
                                <div class="ez-page-title py-3">
                                    <h1 class="ez-page-title__content-item">
                                        <img src={Logo} alt="" className="ez-icon" />
                                        <div class="ez-page-title__content-name" title="Home">
                                            {transTitle}
                                        </div>
                                    </h1>
                                    <h4 class="ez-page-title__content-type-name">{this.props.contentName}</h4>
                                </div>
                            </div>
                        </div>
                        <div className="tab-content container mt-4 pb-3">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="alert alert-danger"> {transErrorNotConfigured} </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        )
    }
}

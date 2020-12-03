import * as HTTPHelper from '../../services/http.helper';


export const getConfiguration = (contentId, callback) => {

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    const method = 'GET';

    const route = HTTPHelper.SEO_GET_CONFIGURATION_ROUTE + "?contentId=" + contentId;

    HTTPHelper.makeRequest(headers, method, null, route, function(err, res) {
        return callback(err, res);
    })
}

export const updateConfiguration = (contentId, keyword, isPillarContent, languageCode, callback) => {

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    const method = 'PUT';

    const route = HTTPHelper.SEO_PUT_CONFIGURATION_ROUTE;

    const body = {
        contentId,
        keyword,
        isPillarContent,
        languageCode
    };

    HTTPHelper.makeRequest(headers, method, body, route, function(err, res) {
        return callback(err, res);
    })
}
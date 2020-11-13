export const SEO_ANALYSIS_ROUTE = '/api/seo/analysis';
export const SEO_GET_CONFIGURATION_ROUTE = '/api/seo/content-configuration/get'
export const SEO_PUT_CONFIGURATION_ROUTE = '/api/seo/content-configuration/update';

export const handleRequestResponse = response => {
    if (!response.ok) {
        throw Error(response.statusText);
    }

    return response.json();
};


export const makeRequest = (headers, method, body, route, callback) => {
    body = JSON.stringify(body);
    const request = new Request(route, {
        method,
        headers,
        body,
        mode: 'cors'
    });

    fetch(request)
        .then(handleRequestResponse)
        .then((res) => {
            callback(null, res)
        })
        .catch((error) => {
            callback(error, null);
        })
}

export const checkContextData = (contextData) => {
    const requiredKeys = [
        "contentId",
        "locationId",
        "contentTypeIdentifier"
    ]
    Object.keys(requiredKeys).map(key => {
        if (!key in contextData) {
            return false;
        }
    });
    if (requiredKeys['contentId'] == "0" || requiredKeys['contentTypeIdentifier'] == "") {
        return false;
    }
    return true;
}
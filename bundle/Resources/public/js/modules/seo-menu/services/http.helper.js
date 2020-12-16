export const SEO_ANALYSIS_ROUTE = '/api/seo/analysis';
export const SEO_CONFIGURATION_ROUTE = '/api/seo/content-configuration'

export const handleRequestResponse = (response) => {

    return (response)
};


export const makeRequest = (headers, method, body, route, callback) => {
    body = body ? JSON.stringify(body) : null;
    const request = new Request(route, {
        method,
        headers,
        body: body,
        mode: 'cors'
    });

    fetch(request)
        .then(response => {
            if (!response.ok) {
                response.json().then(json => {
                    callback(response.statusText, json);
                })
                return;
            }
            response.json().then(json => {
                callback(null, json);
            })
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

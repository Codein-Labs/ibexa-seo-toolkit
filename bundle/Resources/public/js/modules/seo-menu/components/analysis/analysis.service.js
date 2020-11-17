import * as HTTPHelper from '../../services/http.helper';



export const getAnalysis = (contentId, richText, callback) => {

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    const method = 'POST';

    const route = HTTPHelper.SEO_ANALYSIS_ROUTE;

    const body = {
        'keyword': "test",
        'isPillarPage': false,
        'contentTypeIdentifier': 2,
        'fields': [
            {
                'fieldIdentifier': 'description',
                'fieldValue': richText
            }
        ]
    };

    HTTPHelper.makeRequest(headers, method, body, route, function(err, res) {
        return callback(err, res);
    })
}

export const getSeoRichText = () => {
    const configuredValidators = globalThis.eZ.fieldTypeValidators;
    let richTextValidatorIndex = -1;
    for (const [i, v] of configuredValidators.entries()) {
        if (v.hasOwnProperty('alloyEditor')) {
            richTextValidatorIndex = i;
            break;
        }
    }
    if (richTextValidatorIndex === -1) {
        return;
    }
    
    let seoRichText = globalThis.eZ.fieldTypeValidators[richTextValidatorIndex]
        .alloyEditor
        .get('nativeEditor')
        .container
        .$.closest('.ez-data-source')
        .querySelector('textarea')
        .value
    return seoRichText;
}


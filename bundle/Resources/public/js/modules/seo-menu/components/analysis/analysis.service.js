import * as HTTPHelper from '../../services/http.helper';



export const getAnalysis = (context, richTextFields, callback) => {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    const method = 'POST';

    const route = HTTPHelper.SEO_ANALYSIS_ROUTE;

    let body = {
        'fields': richTextFields
    };
    body = Object.assign(context,body);

    HTTPHelper.makeRequest(headers, method, body, route, function(err, res) {
        return callback(err, res);
    })
}

/**
 * Extract the richtext being edited on the page (field identifier and field value)
 * Done via eZ Platform JS global data
 */
export const getSeoRichText = () => {
    const configuredValidators = globalThis.eZ.fieldTypeValidators;
    let richTextValidatorIndexes = [];
    for (const [i, v] of configuredValidators.entries()) {
        if (v.hasOwnProperty('alloyEditor')) {
            richTextValidatorIndexes.push(i);
            break;
        }
    }
    if (richTextValidatorIndexes.length === 0) {
        return;
    }
    let seoRichTextFields = [];
    richTextValidatorIndexes.forEach((element) => {
        let seoRichText = globalThis.eZ.fieldTypeValidators[element]
        .alloyEditor
        .get('nativeEditor')
        .container
        .$.closest('.ez-data-source')
        .querySelector('textarea')
        
        let fieldIdentifier = extractFieldIdentifier(seoRichText.getAttribute('name'));
        if (fieldIdentifier) {
            seoRichTextFields.push({'fieldIdentifier': fieldIdentifier, 'fieldValue': seoRichText.value});
        }
    });
    
    return seoRichTextFields;
}


const extractFieldIdentifier = (textareaNameAttribute) => {
    let matches = textareaNameAttribute.match(/^ezrepoforms_content_edit\[fieldsData\]\[(.*)\]\[value\]/,);
    if (matches.length && 1 in matches) {
        return matches[1];
    }
    return false;
}

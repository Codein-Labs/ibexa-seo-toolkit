const handleRequestResponse = response => {
    if (!response.ok) {
        throw Error(response.statusText);
    }

    return response.json();
};

export const getAnalysis = ({ siteaccess, contentId }, callback) => {
    const body = JSON.stringify({
        
    });
    const request = new Request('/api/seo/analyze', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Siteaccess': siteaccess
        },
        body?,
        mode: 'cors'
    });

    fetch(request)
        .then(handleRequestResponse)
        .then(callback)
        .catch(error => console.log('error:load:analyze', error))
}
export const validateContextData = (contextData) => {
    const requiredKeys = [
        "contentId",
        "locationId",
        "versionNo",
        "contentTypeIdentifier",
        "languageCode"
    ]
    Object.keys(requiredKeys).map(key => {
        if (!(key in contextData)) {
            return false;
        }
    });
    if (
        contextData['contentId'] == 0 ||
        contextData['locationId'] == 0 ||
        contextData['versionNo'] == 0 ||
        contextData['contentTypeIdentifier'] == "" ||
        contextData['languageCode'] == ""
    ) {
        return false;
    }
    return true;
}

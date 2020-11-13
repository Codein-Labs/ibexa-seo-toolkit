export const validateContextData = (contextData) => {
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
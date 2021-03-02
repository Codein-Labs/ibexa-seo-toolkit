<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Helper;

use DOMDocument;

/**
 * Class XmlValidator.
 */
final class XmlValidator
{
    /**
     * @param string $xmlContent A well-formed XML string
     * @param string $version    1.0
     * @param string $encoding   utf-8
     *
     * @return bool
     */
    public static function isXMLContentValid(string $xmlContent, string $version = '1.0', string $encoding = 'utf-8')
    {
        if ('' === \trim($xmlContent)) {
            return false;
        }

        \libxml_use_internal_errors(true);

        $domDocument = new DOMDocument($version, $encoding);
        $domDocument->loadXML($xmlContent);

        $errors = \libxml_get_errors();
        \libxml_clear_errors();

        return empty($errors);
    }
}

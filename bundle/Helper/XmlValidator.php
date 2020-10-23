<?php declare(strict_types=1);

namespace Codein\Tests\eZPlatformSeoToolkit\Helper;

use DOMDocument;

/**
 * Class XmlValidator.
 */
final class XmlValidator
{
    /**
     * @param string $xmlContent A well-formed XML string
     * @param string $version 1.0
     * @param string $encoding utf-8
     * @return bool
     */
    public static function isXMLContentValid($xmlContent, $version = '1.0', $encoding = 'utf-8')
    {
        if ('' === \trim($xmlContent)) {
            return false;
        }

        \libxml_use_internal_errors(true);

        $doc = new DOMDocument($version, $encoding);
        $doc->loadXML($xmlContent);

        $errors = \libxml_get_errors();
        \libxml_clear_errors();

        return empty($errors);
    }
}

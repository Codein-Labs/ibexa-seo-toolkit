<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

/**
 * Class XmlProcessingService.
 */
final class XmlProcessingService
{
    public function combineAndProcessXmlFields($fields, $process = true)
    {
        $xml = '';
        /** @var Field $field */
        foreach ($fields as $key => $field) {
            $fieldXml = $field->getFieldValue();
            if (0 !== $key) {
                $fieldXml = \preg_replace('/^<\?.*\?>(\n)?/', '', $fieldXml);
            }
            $xml .= $fieldXml;
        }

        if ($process) {
            $xmlDocument = new \DOMDocument();
            $xmlDocument->loadXML($xml);

            return $this->processDocument($xmlDocument);
        }

        return $xml;
    }

    private function processDocument(\DOMDocument $xml)
    {
        $xmlStr = $xml->saveHTML();
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->loadHTML($xmlStr);

        return $xml;
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

use Codein\eZPlatformSeoToolkit\Model\Field;

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
            $domDocument = new \DOMDocument();
            $domDocument->loadXML($xml);

            return $this->processDocument($domDocument);
        }

        return $xml;
    }

    private function processDocument(\DOMDocument $domDocument)
    {
        $xmlStr = $domDocument->saveHTML();
        $domDocument = new \DOMDocument('1.0', 'utf-8');
        $domDocument->loadHTML($xmlStr);

        return $domDocument;
    }
}

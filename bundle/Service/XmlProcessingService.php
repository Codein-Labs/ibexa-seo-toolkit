<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Service;

use Codein\IbexaSeoToolkit\Model\Field;
use DOMDocument;

/**
 * Class XmlProcessingService.
 */
final class XmlProcessingService
{
    public function combineAndProcessXmlFields($fields): DOMDocument
    {
        $xmlDocument = new DOMDocument();

        /** @var Field $field */
        foreach ($fields as $key => $field) {
            if (0 === $key) {
                $xmlDocument->loadXML($field->getFieldValue());
            } else {
                $fieldXMLDocument = new DOMDocument();
                $fieldXMLDocument->loadXML($field->getFieldValue());
                foreach ($fieldXMLDocument->firstChild->childNodes as $childNode) {
                    $domNode = $xmlDocument->importNode($childNode, true);
                    $xmlDocument->firstChild->appendChild($domNode);
                }
            }
        }

        $domDocument = new DOMDocument('1.0', 'utf-8');
        $domDocument->loadHTML($xmlDocument->saveHTML());

        return $domDocument;
    }
}

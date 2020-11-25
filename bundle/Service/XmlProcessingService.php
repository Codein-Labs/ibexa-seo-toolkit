<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

/**
 * Class XmlProcessingService.
 */
final class XmlProcessingService
{

    public function processDocument(\DOMDocument $xml) {
        $xmlStr = $xml->saveHTML();
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->loadHTML($xmlStr);
        return $xml;
    }
}
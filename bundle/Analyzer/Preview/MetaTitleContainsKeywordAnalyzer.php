<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter as RichTextConverterInterface;

/**
 * Class CountWordService.
 */
final class MetaTitleContainsKeywordAnalyzer implements ContentPreviewAnalyzerInterface
{
    private $xhtml5Converter;

    const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';


    public function __construct()
    {
        
    }

    public function analyze(array $data): array
    {
        $selector = new \DOMXPath($data['previewHtml']);
        $metaTitle = $selector->query('//meta[@name="title"]');

        $status = 'medium';
        if ($metaTitle->count() == 0) {
            $status = 'low';
        }
        else if (strpos($metaTitle->item(0)->getAttribute('content'), $data['keyword']) !== false) {
            $status = 'high';
        }
        return [ 
            self::CATEGORY => [
                'status' => $status,
                'data' => [],
            ]
        ];
    }

    public function support(): bool
    {
        return true;
    }
}

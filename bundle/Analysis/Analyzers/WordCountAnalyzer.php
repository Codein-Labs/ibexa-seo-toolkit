<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\AnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\XmlProcessingService;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter as RichTextConverterInterface;

/**
 * Class WordCountAnalyzer.
 */
final class WordCountAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{
    const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    /** @var XmlProcessingService */
    private $xmlProcessingService;

    public function __construct(XmlProcessingService $xmlProcessingService)
    {
        $this->xmlProcessingService = $xmlProcessingService;
    }

    public function analyze(AnalysisDTO $data): array
    {
        $fields = $data->getFields();
        
        \libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields)->saveHTML();

        $text = \strip_tags($html);

        $count = \str_word_count($text);
        $status = 'low';

        if ($count > 700 && $count < 1500) {
            $status = 'medium';
        } elseif ($count >= 1500) {
            $status = 'high';
        }

        return [
            self::CATEGORY => [
                'status' => $status,
                'data' => [
                    'count' => $count,
                ],
            ],
        ];
    }
}

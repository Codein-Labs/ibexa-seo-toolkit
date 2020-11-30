<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\RichText;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter as RichTextConverterInterface;

/**
 * Class WordCountAnalyzer.
 */
final class WordCountAnalyzer implements RichTextAnalyzerInterface
{
    const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';
    private $xhtml5Converter;

    public function __construct(RichTextConverterInterface $xhtml5Converter)
    {
        $this->xhtml5Converter = $xhtml5Converter;
    }

    public function analyze(FieldValue $fieldValue, array $data = []): array
    {
        $xml = $fieldValue->xml;
        $html = $this->xhtml5Converter->convert($xml)->saveHTML();

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

    public function support(FieldDefinition $fieldDefinition, $data): bool
    {
        return 'ezrichtext' === $fieldDefinition->fieldTypeIdentifier;
    }
}

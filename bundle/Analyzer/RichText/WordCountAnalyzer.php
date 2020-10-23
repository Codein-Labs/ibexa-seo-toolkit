<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\RichText;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter as RichTextConverterInterface;

/**
 * Class CountWordService.
 */
final class WordCountAnalyzer implements RichTextAnalyzerInterface
{
    private $xhtml5Converter;

    public function __construct(RichTextConverterInterface $xhtml5Converter)
    {
        $this->xhtml5Converter = $xhtml5Converter;
    }

    public function analyze(Field $fieldDefinition, BaseValue $fieldValue): array
    {
        $xml = $fieldValue->xml;
        $html = $this->xhtml5Converter->convert($xml)->saveHTML();

        $text = \strip_tags($html);

        return [
            'items' => \array_flip(\str_word_count($text, 1)),
            'totalCount' => \str_word_count($text),
        ];
    }

    public function support(Field $fieldDefinition): bool
    {
        return 'ezrichtext' === $fieldDefinition->fieldTypeIdentifier;
    }
}

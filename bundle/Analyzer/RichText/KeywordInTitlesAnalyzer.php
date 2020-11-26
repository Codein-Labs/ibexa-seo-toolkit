<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\RichText;

use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Codein\eZPlatformSeoToolkit\Service\XmlProcessingService;
use DOMDocument;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter as RichTextConverterInterface;

/**
 * Class CountWordService.
 */
final class KeywordInTitlesAnalyzer implements RichTextAnalyzerInterface
{
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService $as */
    private $as; 

    /** @var \Codein\eZPlatformSeoToolkit\Service\XmlProcessingService $xmlProcessingService */
    private $xmlProcessingService;

    const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';

    const ACCENT_VALUES = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );

    public function __construct(AnalyzerService $analyzerService, XmlProcessingService $xmlProcessingService)
    {
        $this->as = $analyzerService;
        $this->xmlProcessingService = $xmlProcessingService;
    }

    public function analyze(FieldValue $fieldValue, array $data = []): array
    {
        libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        $xml = $fieldValue->xml;        
        $html = $this->xmlProcessingService->processDocument($xml);

        $selector = new \DOMXPath($html);
        
        $titles = $selector->query('//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]');

        $numberOfTitles = 0;
        $numberOfTitlesContainingKeyword = 0;
        foreach($titles as $title) 
        {
            /** @var \DOMElement $title */
            $titleLowercase = strtr(mb_strtolower($title->textContent), self::ACCENT_VALUES);
            $preprocessedKeyword = strtr(mb_strtolower($data['keyword']), self::ACCENT_VALUES);
            if ($titleLowercase == $preprocessedKeyword) 
            {
                $numberOfTitlesContainingKeyword++;
            }
            $numberOfTitles++;
        }

        $status = 'low';
        $ratioKeywordInTitle = 0;
        if ($numberOfTitles > 0) {
            $ratioKeywordInTitle = round($numberOfTitlesContainingKeyword / $numberOfTitles * 100, 2); 

            if ($ratioKeywordInTitle > 10 && $ratioKeywordInTitle < 40 ) {
                $status = 'medium';
            }
            else if ($ratioKeywordInTitle >= 40) {
                $status = 'high';
            }
        }

        $analysisData = [
            'ratio' => $ratioKeywordInTitle
        ];
        
        return $this->as->compile(self::CATEGORY, $status, $analysisData);
    }

    public function support(FieldDefinition $fieldDefinition, $data): bool
    {
        return 'ezrichtext' === $fieldDefinition->fieldTypeIdentifier;
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\Preview;

use Codein\eZPlatformSeoToolkit\Analyzer\Preview\ContentPreviewAnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use eZ\Publish\Core\Repository\LocationService;
use eZ\Publish\Core\Repository\URLAliasService;

/**
 * Class KeywordInUrlSlugAnalyzer.
 * 
 * Look for keyword in URL Slug
 * 
 * This could be implemented as a richtext analyzer as well as it doesn't need access to content specifically
 */
final class KeywordInUrlSlugAnalyzer implements ContentPreviewAnalyzerInterface
{
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService $as */
    private $as;

    /** @var URLAliasService $urlAliasService */
    private $urlAliasService;

    /** @var LocationService $locationService */
    private $locationService;

    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';


    public function __construct(
        AnalyzerService $analyzerService,
        URLAliasService $urlAliasService,
        LocationService $locationService
    )
    {
        $this->as = $analyzerService;
        $this->urlAliasService = $urlAliasService;
        $this->locationService = $locationService;
    }

    public function analyze(array $data): array
    {
        $locationId = $data['locationId'];

        try {
            $location = $this->locationService->loadLocation($locationId);
    
            /** @var \eZ\Publish\API\Repository\Values\Content\URLAlias $urlAlias */
            $urlAlias = $this->urlAliasService->reverseLookup($location);
    
            $pathArray = explode('/', $urlAlias->path);
            $urlSlug = mb_strtolower(end($pathArray));
            $urlSlugWithoutDashes = str_replace('-', " ", $urlSlug);
            $keyword = strtr(mb_strtolower($data['keyword']), AnalyzerService::ACCENT_VALUES);
            
            $distance = AnalyzerService::levenshtein_utf8($keyword, $urlSlugWithoutDashes);
            $lenSum = strlen($urlSlugWithoutDashes) + strlen($keyword);
            $levenshteinRatio = 1 - ($distance / $lenSum);
    
            $status = 'low';

            if ($levenshteinRatio > 0.85 && $levenshteinRatio < 1)
            {
                $status = 'medium';
            }
            else if ($levenshteinRatio == 1) {
                $status = 'high';
            }
            
            // case keyword is included, but far from equal
            if ($status == 'low' && strpos($urlSlugWithoutDashes, $keyword) !== false) {
                $status = "medium";
            }
        }
        catch(\Exception $e) {
            return $this->as->compile(self::CATEGORY, null, null);
        }
        
        return $this->as->compile(self::CATEGORY, $status, [
            'similarity' => $levenshteinRatio * 100 
        ]);
    }

    public function support($data): bool
    {
        return true;
    }
}

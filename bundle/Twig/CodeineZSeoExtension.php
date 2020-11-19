<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Twig;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use eZ\Publish\API\Repository\Values\Content\Location;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use EzSystems\EzPlatformAdminUi\Siteaccess\SiteaccessResolver;

/**
 * Class CodeineZSeoExtension.
 */
final class CodeineZSeoExtension extends AbstractExtension implements GlobalsInterface
{
    /** @var SiteAccessConfigResolver */
    private $siteAccessConfigResolver;

    /** @var array */
    private $siteAccessesByLanguage;

    /**
     * CodeineZSeoExtension constructor.
     * @param $configResolver
     */
    public function __construct(SiteAccessConfigResolver $configResolver, array $siteAccessesByLanguage)
    {
        $this->siteAccessConfigResolver = $configResolver;
        $this->siteAccessesByLanguage = $siteAccessesByLanguage;
    }

    public function getGlobals(): array
    {
        $metas = $this->siteAccessConfigResolver->getParameterConfig('metas');
        $codeinEzSeo = [
            'field_type_metas' => $metas['field_type_metas'],
            'default_metas' => $metas['default_metas'],
        ];

        return ['codein_ezseo' => $codeinEzSeo];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('codein_siteaccesses_by_language', [$this, 'getSiteaccessesByLanguage']),
        ];
    }

    /**
     * Find potential siteaccesses for this language code
     *
     * @param string $languageCode
     * @return string
     */
    public function getSiteaccessesByLanguage(string $languageCode): string
    {
        $siteAccesses = [];
        if ($languageCode && array_key_exists($languageCode, $this->siteAccessesByLanguage)) {
            $siteAccesses = $this->siteAccessesByLanguage[$languageCode];
        }
        return \json_encode($siteAccesses);
    }
}

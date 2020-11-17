<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Twig;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Class CodeineZSeoExtension.
 */
final class CodeineZSeoExtension extends AbstractExtension implements GlobalsInterface
{
    /** @var SiteAccessConfigResolver */
    private $siteAccessConfigResolver;

    /**
     * CodeineZSeoExtension constructor.
     * @param $configResolver
     */
    public function __construct(SiteAccessConfigResolver $configResolver)
    {
        $this->siteAccessConfigResolver = $configResolver;
    }

    public function getGlobals(): array
    {
        $metas = $this->siteAccessConfigResolver->getParameterConfig('metas');
        $codeineZseo = [
            'field_type_metas' => $metas['field_type_metas'],
            'default_metas' => $metas['default_metas'],
        ];

        return ['codein_ezseo' => $codeineZseo];
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Twig;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

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
        $fieldtypeMetas = $this->siteAccessConfigResolver->getParameterConfig('metas')['field_type'];

        $codeineZseo = [
            'fieldtype_metas' => $fieldtypeMetas,
        ];

        return ['codein_ezseo' => $codeineZseo];
    }
}

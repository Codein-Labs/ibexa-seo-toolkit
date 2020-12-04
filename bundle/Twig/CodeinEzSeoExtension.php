<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Twig;

use Codein\eZPlatformSeoToolkit\FieldType\Value;
use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Repository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

/**
 * Class CodeinEzSeoExtension.
 */
final class CodeinEzSeoExtension extends AbstractExtension implements GlobalsInterface
{
    /** @var SiteAccessConfigResolver */
    private $siteAccessConfigResolver;
    /** @var Repository */
    private $eZRepository;

    /** @var array */
    private $siteAccessesByLanguage;

    /**
     * CodeinEzSeoExtension constructor.
     * @param $configResolver
     */
    public function __construct(SiteAccessConfigResolver $configResolver, Repository $eZRepository, array $siteAccessesByLanguage)
    {
        $this->siteAccessConfigResolver = $configResolver;
        $this->eZRepository = $eZRepository;
        $this->siteAccessesByLanguage = $siteAccessesByLanguage;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('resolve_pattern', [$this, 'resolvePattern']),
            new TwigFunction('codein_siteaccesses_by_language', [$this, 'getSiteaccessesByLanguage']),
        ];
    }

    public function resolvePattern(
        Field $field,
        array $fieldSettings,
        Content $content
    ): array {
        $metasFieldValue = $field->value;
        $filedDefinitionMetas = $fieldSettings['configuration'];

        if (!$metasFieldValue instanceof Value) {
            throw new \Exception(\sprintf('Expected argument of type "%s", "%s" given', Value::class, \is_object($metasFieldValue) ? \get_class($metasFieldValue) : \gettype($metasFieldValue)));
        }
        $metasConfig = $this->siteAccessConfigResolver->getParameterConfig('metas')['field_type_metas'];

        $fieldMetas = $field->value->metas;
        $mainLanguageCode = $content->getVersionInfo()->getContentInfo()->mainLanguageCode;

        foreach ($metasConfig as $key => $entry) {
            if (false === \array_key_exists($key, $fieldMetas)) {
                unset($fieldMetas[$key]);
                continue;
            }

            if (true === empty($fieldMetas[$key])) {
                if (false === empty($filedDefinitionMetas[$key])) {
                    $fieldMetas[$key] = $filedDefinitionMetas[$key];
                } elseif (!empty($metasConfig[$key]['default_pattern'])) {
                    $fieldMetas[$key] = $metasConfig[$key]['default_pattern'];
                }
            }
        }

        foreach ($fieldMetas as $key => $nameSchema) {
            if (false === \array_key_exists($key, $metasConfig)) {
                unset($fieldMetas[$key]);
                continue;
            }
            if ($nameSchema) {
                $metaContent = $this->eZRepository->getNameSchemaService()->resolve($nameSchema, $content->getContentType(), $content->fields, [$mainLanguageCode]);
                $fieldMetas[$key] = $metaContent[$mainLanguageCode];
            }
        }

        return $fieldMetas;
    }

    public function getGlobals(): array
    {
        $metas = $this->siteAccessConfigResolver->getParameterConfig('metas');
        $codeinEzSeo = [
            'field_type_metas' => $metas['field_type_metas'],
            'default_metas' => $metas['default_metas'],
        ];

        return [
            'codein_ezseo' => $codeinEzSeo,
        ];
    }

    /**
     * Find potential siteaccesses for this language code.
     */
    public function getSiteaccessesByLanguage(string $languageCode): string
    {
        $siteAccesses = [];
        if ($languageCode && \array_key_exists($languageCode, $this->siteAccessesByLanguage)) {
            $siteAccesses = $this->siteAccessesByLanguage[$languageCode];
        }

        return \json_encode($siteAccesses);
    }
}

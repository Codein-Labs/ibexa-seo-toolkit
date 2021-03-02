<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Twig;

use Codein\IbexaSeoToolkit\FieldType\Value;
use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Repository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

/**
 * Class CodeinIbexaSeoExtension.
 */
final class CodeinIbexaSeoExtension extends AbstractExtension implements GlobalsInterface
{
    private const FIELD_TYPE_METAS = 'field_type_metas';

    private $eZRepository;
    private $siteAccessesByLanguage;
    private $siteAccessConfigResolver;

    /**
     * CodeinIbexaSeoExtension constructor.
     *
     * @param $configResolver
     */
    public function __construct(
        SiteAccessConfigResolver $configResolver,
        Repository $eZRepository,
        array $siteAccessesByLanguage
    ) {
        $this->siteAccessConfigResolver = $configResolver;
        $this->eZRepository = $eZRepository;
        $this->siteAccessesByLanguage = $siteAccessesByLanguage;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('resolve_pattern', function (Field $field, array $fieldSettings, Content $content): array {
                return $this->resolvePattern($field, $fieldSettings, $content);
            }),
            new TwigFunction('codein_siteaccesses_by_language', function (string $languageCode): string {
                return $this->getSiteaccessesByLanguage($languageCode);
            }),
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
        $metasConfig = $this->siteAccessConfigResolver->getParameterConfig('metas')[self::FIELD_TYPE_METAS];

        $fieldMetas = $field->value->metas;
        $mainLanguageCode = $content->getVersionInfo()->getContentInfo()->mainLanguageCode;

        foreach (\array_keys($metasConfig) as $key) {
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
                $metaContent = $this->eZRepository->getNameSchemaService()
                    ->resolve($nameSchema, $content->getContentType(), $content->fields, [$mainLanguageCode]);
                $fieldMetas[$key] = $metaContent[$mainLanguageCode];
            }
        }

        return $fieldMetas;
    }

    public function getGlobals(): array
    {
        $metas = $this->siteAccessConfigResolver->getParameterConfig('metas');
        $codeinEzSeo = [
            self::FIELD_TYPE_METAS => $metas[self::FIELD_TYPE_METAS],
            'default_metas' => $metas['default_metas'],
        ];

        return [
            'codein_ibexaseo' => $codeinEzSeo,
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

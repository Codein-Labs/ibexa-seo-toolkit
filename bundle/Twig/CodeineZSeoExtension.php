<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Twig;

use Codein\eZPlatformSeoToolkit\FieldType\Value;
use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Helper\NameSchemaService;
use eZ\Publish\Core\Repository\Repository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

/**
 * Class CodeineZSeoExtension.
 */
final class CodeineZSeoExtension extends AbstractExtension implements GlobalsInterface
{
    /** @var SiteAccessConfigResolver */
    private $siteAccessConfigResolver;
    /** @var Repository */
    private $eZRepository;

    /**
     * CodeineZSeoExtension constructor.
     * @param $configResolver
     */
    public function __construct(SiteAccessConfigResolver $configResolver, Repository $eZRepository)
    {
        $this->siteAccessConfigResolver = $configResolver;
        $this->eZRepository = $eZRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('resolve_pattern', [$this, 'resolvePattern']),
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
        $codeineZseo = [
            'field_type_metas' => $metas['field_type_metas'],
            'default_metas' => $metas['default_metas'],
        ];

        return ['codein_ezseo' => $codeineZseo];
    }
}

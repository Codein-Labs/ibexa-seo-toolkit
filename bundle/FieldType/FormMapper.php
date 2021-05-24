<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\FieldType;

use Codein\IbexaSeoToolkit\DependencyInjection\IbexaSeoToolkitExtension;
use Codein\IbexaSeoToolkit\Form\MetasFieldType;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\EzPlatformAdminUi\FieldType\FieldDefinitionFormMapperInterface;
use EzSystems\EzPlatformAdminUi\Form\Data\FieldDefinitionData;
use EzSystems\EzPlatformContentForms\Data\Content\FieldData;
use EzSystems\EzPlatformContentForms\FieldType\FieldValueFormMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormMapper.
 */
final class FormMapper implements FieldDefinitionFormMapperInterface, FieldValueFormMapperInterface
{
    private const CONFIGURATION = 'configuration';
    private const REQUIRED = 'required';
    /** @var ConfigResolverInterface */
    private $configResolver;

    /**
     * FormMapper constructor.
     */
    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * ContentType create / edit view.
     *
     * @param FormInterface       $fieldDefinitionForm form for current FieldDefinition
     * @param FieldDefinitionData $data                underlying data for current FieldDefinition form
     */
    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data): void
    {
        $metasConfig = $this->configResolver->getParameter('metas', IbexaSeoToolkitExtension::ALIAS)['field_type_metas'];

        $aConfigurations = $data->fieldDefinition->fieldSettings[self::CONFIGURATION];
        // we add new field_type_metas entries
        foreach (\array_keys($metasConfig) as $key) {
            if (empty($aConfigurations[$key])) {
                $aConfigurations[$key] = '';
            }
        }

        // we remove unused field_type_metas entries
        foreach ($aConfigurations as $key => $content) {
            if (false === \array_key_exists($key, $metasConfig)) {
                unset($aConfigurations[$key]);
            }
        }
        $data->fieldSettings[self::CONFIGURATION] = $aConfigurations;
        $fieldDefinitionForm
            ->add(
                self::CONFIGURATION,
                DynamicCollectionType::class,
                [
                    'entry_type' => TextType::class,
                    'entry_options' => [
                        self::REQUIRED => false,
                    ],
                    'meta_config' => $metasConfig,
                    self::REQUIRED => false,
                    'property_path' => 'fieldSettings[configuration]',
                    'label' => 'field_definition.codeinseometas.configuration',
                ]
            )
      ;
    }

    /**
     * Content create / edit view.
     *
     * @param FormInterface $fieldForm form for the current Field
     * @param FieldData     $data      underlying data for current Field form
     */
    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data): void
    {
        $fieldDefinition = $data->fieldDefinition;

        $formConfig = $fieldForm->getConfig();

        $metasConfig = $this->configResolver->getParameter('metas', IbexaSeoToolkitExtension::ALIAS)['field_type_metas'];

        // we add new field_type_metas entries
        foreach (\array_keys($metasConfig) as $key) {
            if (empty($data->value->metas[$key])) {
                $data->value->metas[$key] = '';
            }
        }

        // we remove unused field_type_metas entries
        foreach ($data->value->metas as $key => $content) {
            if (false === \array_key_exists($key, $metasConfig)) {
                unset($data->value->metas[$key]);
            }
        }

        $fieldForm
            ->add(
                $formConfig->getFormFactory()
                    ->createBuilder()
                    ->create(
                        'value',
                        MetasFieldType::class,
                        [
                            self::REQUIRED => $fieldDefinition->isRequired,
                            'label' => $fieldDefinition->getName($formConfig->getOption('languageCode')),
                        ]
                    )
                    ->setAutoInitialize(false)
                    ->getForm()
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'codeinseo_content_type');
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\FieldType;

use Codein\eZPlatformSeoToolkit\DependencyInjection\EzPlatformSeoToolkitExtension;
use Codein\eZPlatformSeoToolkit\Form\MetasFieldType;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
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
        $metasConfig = $this->configResolver->getParameter('metas', EzPlatformSeoToolkitExtension::ALIAS)['field_type'];

        $aConfigurations = $data->fieldDefinition->fieldSettings[self::CONFIGURATION];
        foreach (\array_keys($metasConfig) as $key) {
            if (!isset($aConfigurations[$key])) {
                $aConfigurations[$key] = '';
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

        $metasConfig = $this->configResolver->getParameter('metas', EzPlatformSeoToolkitExtension::ALIAS)['field_type'];

        if (empty($data->value->metas)) {
            foreach (\array_keys($metasConfig) as $key) {
                $data->value->metas[$key] = '';
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

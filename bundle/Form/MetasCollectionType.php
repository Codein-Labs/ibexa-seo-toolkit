<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MetasCollectionType.
 */
final class MetasCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'allow_add' => false,
                'allow_delete' => false,
                'entry_type' => TextType::class,
                'entry_options' => [
                    'required' => false,
                ],
                'required' => false,
                'label' => 'field_definition.codeinseometas.fields',
                'translation_domain' => 'fieldtypes',
            ]
        );
    }

    public function getParent(): string
    {
        return CollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'codeinseo_field_type_metas_metas';
    }
}

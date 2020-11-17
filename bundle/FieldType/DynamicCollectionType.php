<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\FieldType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DynamicTextType.
 */
final class DynamicCollectionType extends AbstractType
{
    /**
     * @var string
     */
    private const META_CONFIG = 'meta_config';
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $metaConfigs = $options[self::META_CONFIG];
        $formBuilder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($metaConfigs, $formBuilder): void {
                $form = $event->getForm();

                $formViewData = $form->getViewData();
                foreach ($formViewData as $field => $value) {
                    if (true === \array_key_exists(
                        $field,
                        $metaConfigs
                    ) && !empty($metaConfigs[$field]['default_choices'])) {
                        $choices = $metaConfigs[$field]['default_choices'];

                        $form->add($field, TextChoiceType::class, [
                            'choices' => \array_combine($choices, $choices),
                            'multiple' => true,
                            'expanded' => false,
                        ]);
                    }
                }
            }
        );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                self::META_CONFIG => [],
                'allow_add' => false,
                'allow_delete' => false,
                'entry_type' => TextType::class,
                'entry_options' => ['required' => false],
                'required' => false,
                'label' => 'field_definition.codeinseometas.fields',
                'translation_domain' => 'fieldtypes',
            ])
            ->setAllowedTypes(self::META_CONFIG, ['array', 'null']);
    }

    public function getParent(): string
    {
        return CollectionType::class;
    }
}

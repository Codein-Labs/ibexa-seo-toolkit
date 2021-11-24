<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\FieldType;

use Codein\IbexaSeoToolkit\Event\MetaFieldFormEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
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

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $metaConfigs = $options[self::META_CONFIG];

        foreach ($metaConfigs as $key => $metaConfig) {
            $metaConfig['key'] = $key;
            $this->eventDispatcher->dispatch(new MetaFieldFormEvent($formBuilder, $metaConfig));
        }

        $formBuilder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($metaConfigs): void {
                $form = $event->getForm();

                $formViewData = $form->getViewData();
                foreach (\array_keys($formViewData) as $field) {
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
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver
            ->setDefaults([
                self::META_CONFIG => [],
                'allow_add' => false,
                'allow_delete' => false,
                'entry_type' => TextType::class,
                'entry_options' => [
                    'required' => false,
                ],
                'required' => false,
                'label' => 'field_definition.codeinseometas.fields',
                'translation_domain' => 'fieldtypes',
            ])
            ->setAllowedTypes(self::META_CONFIG, ['array', 'null']);
    }

}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form\Type;

use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContentConfigurationType.
 */
final class ContentConfigurationType extends AbstractType
{
    private const CONSTRAINTS = 'constraints';

    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('keyword', TextType::class, [
            self::CONSTRAINTS => [
                new Assert\Type('string'),
            ],
        ]);
        $formBuilder->add('isPillarContent', CheckboxType::class, [
            self::CONSTRAINTS => [
                new Assert\Type('boolean'),
            ],
        ]);
        $formBuilder->add('contentId', IntegerType::class, [
            self::CONSTRAINTS => [
                new Assert\NotBlank(),
                new Assert\Type('integer'),
            ],
        ]);
        $formBuilder->add('languageCode', TextType::class, [
            self::CONSTRAINTS => [
                new Assert\Type('string'),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => ContentConfiguration::class,
            'csrf_protection' => false,
        ]);
    }
}

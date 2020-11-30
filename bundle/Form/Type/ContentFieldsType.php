<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form\Type;

use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Class ContentFieldsFormType.
 */
final class ContentFieldsType extends AbstractType
{
    private const CONSTRAINTS = 'constraints';

    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add('contentTypeIdentifier', TextType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('contentId', IntegerType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('locationId', IntegerType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('versionNo', IntegerType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('language', TextType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('siteaccess', TextType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('fields', FieldType::class, [
                self::CONSTRAINTS => [new Count([
                    'min' => 1,
                ])],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContentFields::class,
            'csrf_protection' => false,
        ]);
    }
}

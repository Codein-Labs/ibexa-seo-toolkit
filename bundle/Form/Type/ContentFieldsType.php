<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form\Type;

use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContentFieldsFormType.
 */
final class ContentFieldsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class)
            ->add('isPillarPage', CheckboxType::class, [
                'constraints' => [],
            ])
            ->add('contentTypeIdentifier', IntegerType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('fields', FieldType::class, [
                'constraints' => [new Assert\Count(['min' => 1])],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentFields::class,
            'csrf_protection' => false,
        ]);
    }
}

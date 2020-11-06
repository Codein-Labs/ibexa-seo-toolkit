<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form\Type;

use Codein\eZPlatformSeoToolkit\Form\DataTransformer\FieldArrayToObjectTransformer;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FieldFormType.
 */
final class FieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new FieldArrayToObjectTransformer(), true)
          ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => true,
            'csrf_protection' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return TextType::class;
    }
}

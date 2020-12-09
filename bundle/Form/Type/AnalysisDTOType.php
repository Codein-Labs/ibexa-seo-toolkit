<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form\Type;

use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Class AnalysisDTOType.
 */
final class AnalysisDTOType extends AbstractType
{
    private const CONSTRAINTS = 'constraints';

    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add('keyword', TextType::class, [
                self::CONSTRAINTS => [new Assert\NotBlank()],
            ])
            ->add('isPillarContent', CheckboxType::class, [
                self::CONSTRAINTS => [],
            ])
            ->add('previewHtml', TextType::class, [
                self::CONSTRAINTS => [new Assert\NotBlank()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnalysisDTO::class,
            'csrf_protection' => false,
        ]);
    }

    public function getParent()
    {
        return PreAnalysisDTOType::class;
    }
}

<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\FieldType;

use EzSystems\RepositoryForms\Form\Type\FieldType\SelectionFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class TextChoiceType.
 */
final class TextChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->addModelTransformer(new CallbackTransformer(
            function ($tagsAsString) {
                // transform the string back to an array
                $selection = \explode(', ', $tagsAsString);

                return new \eZ\Publish\Core\FieldType\Selection\Value($selection);
            },
            function ($tagsAsArray) {
                if ($tagsAsArray) {
                    $tagsAsArray = $tagsAsArray->selection;
                }
                // transform the array to a string
                return \implode(', ', $tagsAsArray);
            }
        ));
    }

    public function buildView(FormView $formView, FormInterface $form, array $options)
    {
        $formView->vars = \array_merge($formView->vars, [
            'selection' => true,
        ]);
        parent::buildView($formView, $form, $options);
    }

    public function getParent(): string
    {
        return SelectionFieldType::class;
    }
}

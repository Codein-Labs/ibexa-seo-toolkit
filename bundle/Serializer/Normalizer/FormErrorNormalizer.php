<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Serializer\Normalizer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormErrorNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'code' => isset($context['status_code']) ? $context['status_code'] : null,
            'message' => 'Validation Failed',
            'errors' => $this->convertFormToArray($object),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FormInterface && $data->isSubmitted() && !$data->isValid();
    }

    /**
     * This code has been taken from JMSSerializer.
     */
    private function convertFormToArray(FormInterface $data): array
    {
        $form = $errors = [];

        foreach ($data->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        if ($errors) {
            $form['errors'] = $errors;
        }

        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                $children[$child->getName()] = $this->convertFormToArray($child);
            }
        }

        if ($children) {
            $form['children'] = $children;
        }

        return $form;
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Form\DataTransformer;

use Codein\eZPlatformSeoToolkit\Model\Field;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class FieldArrayToObjectTransformer.
 */
final class FieldArrayToObjectTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($fields): array
    {
        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($fields): array
    {
        if (!\is_array($fields)) {
            return [];
        }

        $objects = [];
        foreach ($fields as $fieldArray) {
            $field = new Field();
            if (\array_key_exists('fieldIdentifier', $fieldArray)) {
                $field->setFieldIdentifier($fieldArray['fieldIdentifier']);
            }
            if (\array_key_exists('fieldValue', $fieldArray)) {
                $field->setFieldValue($fieldArray['fieldValue']);
            }
            $objects[] = $field;
        }

        return $objects;
    }
}

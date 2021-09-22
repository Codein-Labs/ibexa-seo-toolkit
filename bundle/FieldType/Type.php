<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\FieldType;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\Core\FieldType\Value as CoreValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;

/**
 * Class Type.
 */
class Type extends FieldType
{
    public const IDENTIFIER = 'codeinseometas';

    /**
     * @var array
     */
    protected $settingsSchema = [
        'configuration' => [
            'type' => 'array',
            'default' => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function validateFieldSettings($fieldSettings): array
    {
        $validationErrors = [];

        foreach ($fieldSettings as $settingKey => $settingValue) {
            switch ($settingKey) {
                case 'configuration':
                    if (!\is_array($settingValue)) {
                        $validationErrors[] = new ValidationError(
                            "FieldType '%fieldType%' expects setting '%setting%' to be of type '%type%'",
                            null,
                            [
                                '%fieldType%' => $this->getFieldTypeIdentifier(),
                                '%setting%' => $settingKey,
                                '%type%' => 'hash',
                            ],
                            "[${settingKey}]"
                        );
                    }
                    break;
                case 'choices':
                    break;
                default:
                    $validationErrors[] = new ValidationError(
                        "Setting '%setting%' is unknown",
                        null,
                        [
                            '%setting%' => $settingKey,
                        ],
                        "[${settingKey}]"
                    );
            }
        }

        return $validationErrors;
    }

    public function getFieldTypeIdentifier()
    {
        return self::IDENTIFIER;
    }

    /**
     * Returns a human readable string representation from the given $value.
     */
    public function getName(SPIValue $value, FieldDefinition $fieldDefinition, string $languageCode): string
    {
        return $value->__toString();
    }

    public function getEmptyValue()
    {
        return new Value();
    }

    public function fromHash($hash)
    {
        if (!\is_array($hash)) {
            return new Value([]);
        }
        $metas = [];
        foreach ($hash as $key => $hashItem) {
            if (!\is_array($hashItem)) {
                continue;
            }

            $metas[$key] = $hashItem;
        }

        return new Value($metas);
    }

    public function toHash(SPIValue $value)
    {
        $hash = [];
        foreach ($value->metas as $name => $content) {
            $hash[$name] = [
                'meta_name' => $name,
                'meta_content' => $content,
            ];
        }

        return $hash;
    }

    /**
     * Converts a $value to a persistence value.
     */
    public function toPersistenceValue(SPIValue $value): FieldValue
    {
        return new FieldValue(
            [
                'data' => null,
                'externalData' => $this->toHash($value),
                'sortKey' => $this->getSortInfo($value),
            ]
        );
    }

    /**
     * Converts a persistence $fieldValue to a Value.
     */
    public function fromPersistenceValue(FieldValue $fieldValue): Value
    {
        return $this->fromHash($fieldValue->externalData);
    }

    /**
     * Returns if the given $value is considered empty by the field type.
     */
    public function isEmptyValue(SPIValue $value): bool
    {
        return null === $value || $value->metas === $this->getEmptyValue()->metas;
    }

    protected function createValueFromInput($inputValue)
    {
        if (\is_array($inputValue)) {
            foreach ($inputValue as $index => $inputValueItem) {
                if (!\is_string($inputValueItem)) {
                    throw new InvalidArgumentType('$inputValue[' . $index . ']', 'string', $inputValueItem);
                }
            }
            $inputValue = new Value($inputValue);
        }

        return $inputValue;
    }

    protected function checkValueStructure(CoreValue $value): void
    {
    }

    /**
     * Returns information for FieldValue->$sortKey relevant to the field type.
     */
    protected function getSortInfo(CoreValue $value): bool
    {
        return false;
    }
}

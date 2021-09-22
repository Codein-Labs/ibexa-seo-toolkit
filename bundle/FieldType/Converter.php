<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\FieldType;

use eZ\Publish\Core\FieldType\FieldSettings;
use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter as LegacyConverter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

/**
 * Class Converter.
 */
final class Converter implements LegacyConverter
{
    private const CONFIGURATION = 'configuration';

    /** @no-named-arguments */
    public function toStorageValue(FieldValue $fieldValue, StorageFieldValue $storageFieldValue): void
    {
        $storageFieldValue->dataText = \json_encode($fieldValue->externalData);
    }

    /**
     * Converts data from $value to $fieldValue.
     */
    public function toFieldValue(StorageFieldValue $storageFieldValue, FieldValue $fieldValue): void
    {
        $fieldValue->externalData = \json_decode($storageFieldValue->dataText, true);
        $fieldValue->sortKey = $storageFieldValue->sortKeyString;
    }

    /**
     * Converts field definition data in $fieldDef into $storageFieldDef.
     */
    public function toStorageFieldDefinition(FieldDefinition $fieldDefinition, StorageFieldDefinition $storageFieldDefinition): void
    {
        $fieldSettings = $fieldDefinition->fieldTypeConstraints->fieldSettings;

        if (isset($fieldSettings[self::CONFIGURATION])) {
            $storageFieldDefinition->dataText5 = \json_encode($fieldSettings[self::CONFIGURATION]);
        }
    }

    /**
     * Converts field definition data in $storageDef into $storageFieldDefinition.
     */
    public function toFieldDefinition(StorageFieldDefinition $storageFieldDefinition, FieldDefinition $fieldDefinition): void
    {
        $fieldDefinition->fieldTypeConstraints->fieldSettings = new FieldSettings(
            [
                self::CONFIGURATION => \json_decode($storageFieldDefinition->dataText5, true),
            ]
        );
    }

    public function getIndexColumn()
    {
        return 'sort_key_string';
    }
}

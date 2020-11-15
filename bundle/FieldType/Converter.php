<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\FieldType;

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

    public function toStorageValue(FieldValue $fieldValue, StorageFieldValue $storageFieldValue): void
    {
        $storageFieldValue->dataText = \json_encode($fieldValue->externalData);
    }

    /**
     * Converts data from $value to $fieldValue.
     */
    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue): void
    {
        $fieldValue->externalData = \json_decode($value->dataText, true);
        $fieldValue->data = \json_decode($value->dataText, true);
        $fieldValue->sortKey = $value->sortKeyString;
    }

    /**
     * Converts field definition data in $fieldDef into $storageFieldDef.
     */
    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef): void
    {
        $fieldSettings = $fieldDef->fieldTypeConstraints->fieldSettings;

        if (isset($fieldSettings[self::CONFIGURATION])) {
            $storageDef->dataText5 = \json_encode($fieldSettings[self::CONFIGURATION]);
        }
    }

    /**
     * Converts field definition data in $storageDef into $fieldDef.
     */
    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
        $fieldDef->fieldTypeConstraints->fieldSettings = new FieldSettings(
            [
                self::CONFIGURATION => \json_decode($storageDef->dataText5, true),
            ]
        );
    }

    public function getIndexColumn()
    {
        return 'sort_key_string';
    }
}

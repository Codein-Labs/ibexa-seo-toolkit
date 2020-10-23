<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

/**
 * Class Field.
 */
class Field
{
    private $fieldIdentifier;
    private $fieldValue;

    /**
     * @return mixed
     */
    public function getFieldIdentifier()
    {
        return $this->fieldIdentifier;
    }

    /**
     * @param mixed $fieldIdentifier
     */
    public function setFieldIdentifier($fieldIdentifier)
    {
        $this->fieldIdentifier = $fieldIdentifier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldValue()
    {
        return $this->fieldValue;
    }

    /**
     * @param mixed $fieldValue
     */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;

        return $this;
    }
}

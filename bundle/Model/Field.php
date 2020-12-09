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
     * @return ?string
     */
    public function getFieldIdentifier()
    {
        return $this->fieldIdentifier;
    }

    /**
     * @param ?string $fieldIdentifier
     */
    public function setFieldIdentifier(?string $fieldIdentifier): self
    {
        $this->fieldIdentifier = $fieldIdentifier;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getFieldValue(): ?string
    {
        return $this->fieldValue;
    }

    /**
     * @param ?string $fieldValue
     */
    public function setFieldValue(?string $fieldValue): self
    {
        $this->fieldValue = $fieldValue;

        return $this;
    }

    private function toArray(): array
    {
        return [
            'fieldIdentifier' => $this->getFieldIdentifier(),
            'fieldValue' => $this->getFieldValue()
        ];
    }
}

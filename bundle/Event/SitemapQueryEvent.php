<?php

namespace Codein\IbexaSeoToolkit\Event;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use Symfony\Contracts\EventDispatcher\Event;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class SitemapQueryEvent extends Event
{
    /** @var LocationQuery */
    private $locationQuery;

    /** @var string */
    private $specificContentType;

    /** @var Criterion[]  */
    private $baseCriteria;

    /** @var Criterion[]  */
    private $blocklistCriteria;

    /** @var Criterion[]  */
    private $passlistCriteria;

    public function __construct(LocationQuery $locationQuery, array $baseCriteria, array $blocklistCriteria, array $passlistCriteria, string $specificContentType)
    {
        $this->locationQuery = $locationQuery;
        $this->specificContentType = $specificContentType;
        $this->baseCriteria = $baseCriteria;
        $this->blocklistCriteria = $blocklistCriteria;
        $this->passlistCriteria = $passlistCriteria;
    }

    /**
     * @return LocationQuery
     */
    public function getLocationQuery(): LocationQuery
    {
        return $this->locationQuery;
    }

    /**
     * @param LocationQuery $locationQuery
     */
    public function setLocationQuery(LocationQuery $locationQuery): void
    {
        $this->locationQuery = $locationQuery;
    }

    /**
     * @return string
     */
    public function getSpecificContentType(): string
    {
        return $this->specificContentType;
    }

    /**
     * @param string $specificContentType
     */
    public function setSpecificContentType(string $specificContentType): void
    {
        $this->specificContentType = $specificContentType;
    }

    public function hasSpecificContentType(): bool
    {
        return !empty($this->specificContentType);
    }

    /**
     * @return Criterion[]
     */
    public function getBaseCriteria(): array
    {
        return $this->baseCriteria;
    }

    /**
     * @param Criterion[] $baseCriteria
     */
    public function setBaseCriteria(array $baseCriteria): void
    {
        $this->baseCriteria = $baseCriteria;
    }

    /**
     * @return Criterion[]
     */
    public function getBlocklistCriteria(): array
    {
        return $this->blocklistCriteria;
    }

    /**
     * @param Criterion[] $blocklistCriteria
     */
    public function setBlocklistCriteria(array $blocklistCriteria): void
    {
        $this->blocklistCriteria = $blocklistCriteria;
    }

    /**
     * @return Criterion[]
     */
    public function getPasslistCriteria(): array
    {
        return $this->passlistCriteria;
    }

    /**
     * @param Criterion[] $passlistCriteria
     */
    public function setPasslistCriteria(array $passlistCriteria): void
    {
        $this->passlistCriteria = $passlistCriteria;
    }
}

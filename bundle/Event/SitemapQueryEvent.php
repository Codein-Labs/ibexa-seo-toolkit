<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Event;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use Symfony\Contracts\EventDispatcher\Event;

class SitemapQueryEvent extends Event
{
    /** @var LocationQuery */
    private $locationQuery;

    /** @var string */
    private $specificContentType;

    /** @var Criterion[] */
    private $baseCriteria;

    /** @var Criterion[] */
    private $blocklistCriteria;

    /** @var Criterion[] */
    private $passlistCriteria;

    public function __construct(LocationQuery $locationQuery, array $baseCriteria, array $blocklistCriteria, array $passlistCriteria, string $specificContentType)
    {
        $this->locationQuery = $locationQuery;
        $this->specificContentType = $specificContentType;
        $this->baseCriteria = $baseCriteria;
        $this->blocklistCriteria = $blocklistCriteria;
        $this->passlistCriteria = $passlistCriteria;
    }

    public function getLocationQuery(): LocationQuery
    {
        return $this->locationQuery;
    }

    public function setLocationQuery(LocationQuery $locationQuery): void
    {
        $this->locationQuery = $locationQuery;
    }

    public function getSpecificContentType(): string
    {
        return $this->specificContentType;
    }

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

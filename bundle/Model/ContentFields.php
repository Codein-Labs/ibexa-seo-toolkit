<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class RichText.
 */
class ContentFields
{
    private $contentTypeIdentifier;
    // private $keyword;
    // private $isPillarPage;
    private $contentId;
    private $versionNo;
    private $locationId;
    private $fields;

    // /**
    //  * @return ?string
    //  */
    // public function getKeyword()
    // {
    //     return $this->keyword;
    // }

    // /**
    //  * @param ?string $keyword
    //  */
    // public function setKeyword(?string $keyword): self
    // {
    //     $this->keyword = $keyword;

    //     return $this;
    // }

    // /**
    //  * @return ?bool
    //  */
    // public function getIsPillarPage()
    // {
    //     return $this->isPillarPage;
    // }

    // /**
    //  * @param bool $isPillarPage
    //  */
    // public function setIsPillarPage(?bool $isPillarPage): self
    // {
    //     $this->isPillarPage = $isPillarPage;

    //     return $this;
    // }

    /**
     * @return Collection|ArrayCollection
     */
    public function getFields(): ?ArrayCollection
    {
        return $this->fields;
    }

    /**
     * @param Field[] $fields
     */
    public function setFields($fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getContentTypeIdentifier()
    {
        return $this->contentTypeIdentifier;
    }

    /**
     * @param ?string $contentTypeIdentifier
     */
    public function setContentTypeIdentifier(?string $contentTypeIdentifier): self
    {
        $this->contentTypeIdentifier = $contentTypeIdentifier;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param ?int $contentId
     */
    public function setContentId(?int $contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @param ?int $locationId
     */
    public function setLocationId(?int $locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getVersionNo()
    {
        return $this->versionNo;
    }

    /**
     * @param ?int $versionNo
     */
    public function setVersionNo(?int $versionNo): self
    {
        $this->versionNo = $versionNo;

        return $this;
    }
}

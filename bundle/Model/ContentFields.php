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
    private $contentId;
    private $versionNo;
    private $locationId;
    private $language;
    private $siteaccess;
    private $fields;

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

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param ?string $language
     */
    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getSiteaccess()
    {
        return $this->siteaccess;
    }

    /**
     * @param ?string $siteaccess
     */
    public function setSiteaccess(?string $siteaccess): self
    {
        $this->siteaccess = $siteaccess;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'contentTypeIdentifier' => $this->contentTypeIdentifier,
            'contentId' => $this->contentId,
            'locationId' => $this->locationId,
            'versionNo' => $this->versionNo,
            'language' => $this->language,
            'siteaccess' => $this->siteaccess,
        ];
    }
}

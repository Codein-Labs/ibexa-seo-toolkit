<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

/**
 * Class AnalysisDTO.
 */
class AnalysisDTO
{
    /** @var bool */
    private $isPillarContent = false;

    /** @var int */
    private $contentId;

    /** @var int */
    private $locationId;

    /** @var int */
    private $versionNo;

    /** @var string */
    private $languageCode;

    /** @var string */
    private $contentTypeIdentifier;

    /** @var string */
    private $siteaccess;

    /** @var Field[] */
    private $fields = [];
    /** @var ?string */
    private $keyword;

    /** @var ?string */
    private $previewHtml;

    /**
     * Get the value of keyword.
     *
     * @return ?string
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * Set the value of keyword.
     *
     * @param ?string $keyword
     */
    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get the value of isPillarContent.
     */
    public function isPillarContent(): bool
    {
        return $this->isPillarContent;
    }

    /**
     * Set the value of isPillarContent.
     *
     * @param bool $isPillarContent
     */
    public function setIsPillarContent($isPillarContent): self
    {
        $this->isPillarContent = $isPillarContent;

        return $this;
    }

    /**
     * Get the value of contentId.
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * Set the value of contentId.
     *
     * @param int $contentId
     */
    public function setContentId($contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * Get the value of locationId.
     */
    public function getLocationId(): int
    {
        return $this->locationId;
    }

    /**
     * Set the value of locationId.
     *
     * @param mixed $locationId
     */
    public function setLocationId($locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get the value of versionNo.
     */
    public function getVersionNo(): int
    {
        return $this->versionNo;
    }

    /**
     * Set the value of versionNo.
     *
     * @param int $versionNo
     */
    public function setVersionNo($versionNo): self
    {
        $this->versionNo = $versionNo;

        return $this;
    }

    /**
     * Get the value of language.
     */
    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    /**
     * Set the value of language.
     *
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode): self
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * Get the value of contentTypeIdentifier.
     */
    public function getContentTypeIdentifier(): string
    {
        return $this->contentTypeIdentifier;
    }

    /**
     * Set the value of contentTypeIdentifier.
     *
     * @param string $contentTypeIdentifier
     */
    public function setContentTypeIdentifier($contentTypeIdentifier): self
    {
        $this->contentTypeIdentifier = $contentTypeIdentifier;

        return $this;
    }

    /**
     * Get the value of siteaccess.
     */
    public function getSiteaccess(): string
    {
        return $this->siteaccess;
    }

    /**
     * Set the value of siteaccess.
     *
     * @param string $siteaccess
     */
    public function setSiteaccess($siteaccess): self
    {
        $this->siteaccess = $siteaccess;

        return $this;
    }

    /**
     * Get the value of fields.
     *
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the value of fields.
     *
     * @param Field[] $fields
     */
    public function setFields($fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get the value of previewHtml.
     *
     * @return ?string
     */
    public function getPreviewHtml(): ?string
    {
        return $this->previewHtml;
    }

    /**
     * Set the value of previewHtml.
     *
     * @param string $previewHtml
     */
    public function setPreviewHtml(?string $previewHtml): self
    {
        $this->previewHtml = $previewHtml;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'contentTypeIdentifier' => $this->contentTypeIdentifier,
            'contentId' => $this->contentId,
            'locationId' => $this->locationId,
            'versionNo' => $this->versionNo,
            'languageCode' => $this->languageCode,
            'siteaccess' => $this->siteaccess,
        ];
    }
}

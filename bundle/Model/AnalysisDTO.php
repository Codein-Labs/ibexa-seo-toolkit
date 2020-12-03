<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

/**
 * Class AnalysisDTO.
 */
class AnalysisDTO
{
    /** @var string */
    private $keyword;

    /** @var bool */
    private $isPillarContent;

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
    private $fields;

    /** @var string */
    private $previewHtml;

    /**
     * Get the value of keyword.
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set the value of keyword.
     *
     * @param mixed $keyword
     * @return  self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get the value of isPillarContent.
     *
     * @return bool
     */
    public function getIsPillarContent()
    {
        return $this->isPillarContent;
    }

    /**
     * Set the value of isPillarContent.
     *
     * @param mixed $isPillarContent
     * @return  self
     */
    public function setIsPillarContent($isPillarContent)
    {
        $this->isPillarContent = $isPillarContent;

        return $this;
    }

    /**
     * Get the value of contentId.
     *
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * Set the value of contentId.
     *
     * @param mixed $contentId
     * @return  self
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * Get the value of locationId.
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set the value of locationId.
     *
     * @param mixed $locationId
     * @return  self
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get the value of versionNo.
     *
     * @return int
     */
    public function getVersionNo()
    {
        return $this->versionNo;
    }

    /**
     * Set the value of versionNo.
     *
     * @param mixed $versionNo
     * @return  self
     */
    public function setVersionNo($versionNo)
    {
        $this->versionNo = $versionNo;

        return $this;
    }

    /**
     * Get the value of language.
     *
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * Set the value of language.
     *
     * @param mixed $languageCode
     * @return  self
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * Get the value of contentTypeIdentifier.
     *
     * @return string
     */
    public function getContentTypeIdentifier()
    {
        return $this->contentTypeIdentifier;
    }

    /**
     * Set the value of contentTypeIdentifier.
     *
     * @param mixed $contentTypeIdentifier
     * @return  self
     */
    public function setContentTypeIdentifier($contentTypeIdentifier)
    {
        $this->contentTypeIdentifier = $contentTypeIdentifier;

        return $this;
    }

    /**
     * Get the value of siteaccess.
     *
     * @return string
     */
    public function getSiteaccess()
    {
        return $this->siteaccess;
    }

    /**
     * Set the value of siteaccess.
     *
     * @param mixed $siteaccess
     * @return  self
     */
    public function setSiteaccess($siteaccess)
    {
        $this->siteaccess = $siteaccess;

        return $this;
    }

    /**
     * Get the value of fields.
     *
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of fields.
     *
     * @param mixed $fields
     * @return  self
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get the value of previewHtml.
     *
     * @return \string
     */
    public function getPreviewHtml()
    {
        return $this->previewHtml;
    }

    /**
     * Set the value of previewHtml.
     *
     * @param mixed $previewHtml
     * @return  self
     */
    public function setPreviewHtml($previewHtml)
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
            'language' => $this->language,
            'siteaccess' => $this->siteaccess,
        ];
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AnalysisDTO.
 */
class PreAnalysisDTO implements ArrayableInterface
{
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

    /** @var ArrayCollection|array */
    private $fields;

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
     * @return ArrayCollection|array
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
     * return array containing entity data.
     */
    public function toArray(): array
    {
        return [
            'contentId' => $this->getContentId(),
            'versionNo' => $this->getVersionNo(),
            'locationId' => $this->getLocationId(),
            'contentTypeIdentifier' => $this->getContentTypeIdentifier(),
            'languageCode' => $this->getLanguageCode(),
            'siteaccess' => $this->getSiteaccess(),
        ];
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="codein_seo_content_configuration")
 */
class ContentConfiguration
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false, unique=true)
     */
    private $contentId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $keyword;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPillarContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $languageCode = 'eng-GB';

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param ?string $keyword
     */
    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * @return ?bool
     */
    public function getIsPillarContent()
    {
        return $this->isPillarContent;
    }

    /**
     * @param bool $isPillarContent
     */
    public function setIsPillarContent(?bool $isPillarContent): self
    {
        $this->isPillarContent = $isPillarContent;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param ?string $contentTypeIdentifier
     * @param ?string $contentId
     */
    public function setContentId(?string $contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param ?string $languageCode
     */
    public function setLanguageCode(?string $languageCode): self
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'keyword' => $this->keyword,
            'isPillarContent' => $this->isPillarContent,
            'contentId' => $this->contentId,
            'languageCode' => $this->languageCode,
        ];
    }
}

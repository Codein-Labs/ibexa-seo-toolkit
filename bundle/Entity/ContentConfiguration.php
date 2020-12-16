<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Entity;

use Codein\eZPlatformSeoToolkit\Model\ArrayableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="codein_seo_content_configuration")
 */
class ContentConfiguration implements ArrayableInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $contentId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keyword = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPillarContent = false;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @return bool
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
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    public function setContentId(int $contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    public function setLanguageCode(string $languageCode): self
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

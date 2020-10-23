<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class RichText.
 */
class ContentFields
{
    private $keyword;
    private $isPillarPage;
    /** @var Field[] */
    private $fields;
    private $contentTypeIdentifier;

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsPillarPage()
    {
        return $this->isPillarPage;
    }

    /**
     * @param mixed $isPillarPage
     */
    public function setIsPillarPage($isPillarPage)
    {
        $this->isPillarPage = $isPillarPage;

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFields(): ?ArrayCollection
    {
        return $this->fields;
    }

    /**
     * @param Field[] $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentTypeIdentifier()
    {
        return $this->contentTypeIdentifier;
    }

    /**
     * @param mixed $contentTypeIdentifier
     */
    public function setContentTypeIdentifier($contentTypeIdentifier)
    {
        $this->contentTypeIdentifier = $contentTypeIdentifier;

        return $this;
    }
}

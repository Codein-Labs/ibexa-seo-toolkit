<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Model;

use DOMDocument;

/**
 * Class AnalysisDTO.
 */
class AnalysisDTO extends PreAnalysisDTO
{
    /** @var bool */
    private $isPillarContent = false;

    /** @var string */
    private $keyword;

    /** @var ?string */
    private $previewHtml;

    /** @var DOMDocument */
    private $contentDOMDocument;

    public function __construct()
    {
        $this->contentDOMDocument = new DOMDocument();
    }

    /**
     * Get the value of keyword.
     */
    public function getKeyword(): string
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
     * Get the value of the full HTML preview.
     *
     * @return ?string
     */
    public function getPreviewHtml(): ?string
    {
        return $this->previewHtml;
    }

    /**
     * Set the value of the full HTML preview.
     * Keeps the analyzableDOMDocument in sync with the HTML preview.
     *
     * @param string $previewHtml
     */
    public function setPreviewHtml(?string $previewHtml): self
    {
        $this->previewHtml = $previewHtml;

        if (null !== $previewHtml) {
            $this->contentDOMDocument->loadHTML($previewHtml);
        } else {
            $this->contentDOMDocument = new DOMDocument();
        }

        return $this;
    }

    /**
     * Get the full HTML preview as DOMDocument.
     */
    public function getHtmlPreviewDOMDocument(): DOMDocument
    {
        $DOMDocument = new DOMDocument();
        $DOMDocument->loadHTML($this->previewHtml);

        return $DOMDocument;
    }

    /**
     * Get the analyzable part of the HTML preview as DOMDocument.
     */
    public function getContentDOMDocument(): DOMDocument
    {
        return $this->contentDOMDocument;
    }

    /**
     * Set the analyzable part of the HTML preview as DOMDocument.
     */
    public function setContentDOMDocument(DOMDocument $contentDOMDocument): self
    {
        $this->contentDOMDocument = $contentDOMDocument;

        return $this;
    }
}

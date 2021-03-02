<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Model;

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

}

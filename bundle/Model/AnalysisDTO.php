<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

/**
 * Class AnalysisDTO.
 */
class AnalysisDTO extends PreAnalysisDTO
{
    /** @var string */
    private $keyword;

    /** @var bool */
    private $isPillarContent;

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

}

<?php

namespace Codein\IbexaSeoToolkit\Event;

use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Symfony\Contracts\EventDispatcher\Event;

class AnalysisDTOEvent extends Event
{
    /** @var AnalysisDTO */
    private $analysisDTO;

    public function __construct(AnalysisDTO $analysisDTO)
    {
        $this->analysisDTO = $analysisDTO;
    }

    /**
     * @return AnalysisDTO
     */
    public function getAnalysisDTO(): AnalysisDTO
    {
        return $this->analysisDTO;
    }

    /**
     * @param AnalysisDTO $analysisDTO
     * @return AnalysisDTOEvent
     */
    public function setAnalysisDTO(AnalysisDTO $analysisDTO): AnalysisDTOEvent
    {
        $this->analysisDTO = $analysisDTO;
        return $this;
    }
}

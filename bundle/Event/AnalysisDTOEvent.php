<?php declare(strict_types=1);

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

    public function getAnalysisDTO(): AnalysisDTO
    {
        return $this->analysisDTO;
    }

    public function setAnalysisDTO(AnalysisDTO $analysisDTO): self
    {
        $this->analysisDTO = $analysisDTO;

        return $this;
    }
}

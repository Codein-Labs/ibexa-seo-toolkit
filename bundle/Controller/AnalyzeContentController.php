<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Controller;

use Codein\IbexaSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\IbexaSeoToolkit\Exception\AnalyzeException;
use Codein\IbexaSeoToolkit\Exception\ValidationException;
use Codein\IbexaSeoToolkit\Form\Type\AnalysisDTOType;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Service\AnalyzeContentService;
use eZ\Publish\Core\MVC\Symfony\Controller\Content\PreviewController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AnalyzeContentController.
 * @Rest\View()
 */
final class AnalyzeContentController
{
    private $analyzeContentService;
    private $previewControllerService;
    private $parentAnalyzerService;
    private $formFactory;

    /**
     * AnalyzeContentController constructor.
     */
    public function __construct(
        PreviewController $previewControllerService,
        AnalyzeContentService $analyzeContentService,
        ParentAnalyzerService $parentAnalyzerService,
        FormFactoryInterface $formFactory
    ) {
        $this->analyzeContentService = $analyzeContentService;
        $this->previewControllerService = $previewControllerService;
        $this->parentAnalyzerService = $parentAnalyzerService;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request)
    {
        $analysisDTO = new AnalysisDTO();
        $form = $this->formFactory->create(AnalysisDTOType::class, $analysisDTO);

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new ValidationException('codein_seo_toolkit.analyzer.error.data_transfered');
        }

        // Select fields according to allowed richText field DI configuration.
        $filteredDataFields = $this->analyzeContentService->manageRichTextDataFields(
            $analysisDTO->getFields()->toArray(),
            $analysisDTO->getContentTypeIdentifier(),
            $analysisDTO->getSiteaccess()
        );
        $analysisDTO->setFields($filteredDataFields);

        // Retrieving content preview data
        $dataPreviewHtml = $this->previewControllerService->previewContentAction($request,
            $analysisDTO->getContentId(),
            $analysisDTO->getVersionNo(),
            $analysisDTO->getLanguageCode(),
            $analysisDTO->getSiteaccess()
        )->getContent();
        if (!$dataPreviewHtml || 0 === \mb_strlen($dataPreviewHtml)) {
            throw new ValidationException('codein_seo_toolkit.analyzer.error.preview_not_returning_html');
        }

        try {
            $contentConfiguration = $this->analyzeContentService->addContentConfigurationToDataArray($analysisDTO);
        } catch (\Exception $e) {
            throw new AnalyzeException('codein_seo_toolkit.analyzer.error.content_not_configured');
        }

        $analysisDTO->setIsPillarContent($contentConfiguration->getIsPillarContent())
            ->setKeyword($contentConfiguration->getKeyword())
            ->setPreviewHtml($dataPreviewHtml);

        $analyzeResult = $this->parentAnalyzerService->analyze($analysisDTO);

        if (\array_key_exists('error', $analyzeResult)) {
            throw new AnalyzeException('codein_seo_toolkit.analyzer.error.content_not_configured');
        }

        return $analyzeResult;
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Form\Type\AnalysisDTOType;
use Codein\eZPlatformSeoToolkit\Form\Type\PreAnalysisDTOType;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Model\Field;
use Codein\eZPlatformSeoToolkit\Model\PreAnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzeContentService;
use eZ\Publish\Core\MVC\Symfony\Controller\Content\PreviewController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentController.
 */
final class AnalyzeContentController extends AbstractController
{
    private $analyzeContentService;
    private $previewControllerService;

    /**
     * AnalyzeContentController constructor.
     */
    public function __construct(
        PreviewController $previewControllerService,
        AnalyzeContentService $analyzeContentService
    ) {
        $this->analyzeContentService = $analyzeContentService;
        $this->previewControllerService = $previewControllerService;
    }

    public function __invoke(Request $request)
    {
        /** @var ParameterBag $data */
        $data = $request->request->all();

        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new HttpException(400, 'Invalid json.');
        }

        $form = $this->createForm(PreAnalysisDTOType::class, new PreAnalysisDTO());
        $form->submit($data);
        if (!$form->isValid()) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'codein_seo_toolkit.analyzer.error.data_transfered',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var PreAnalysisDTO */
        $preAnalysisData = $form->getData();
        
        /** @var Field */
        $fields = $preAnalysisData->getFields();
        // Reorder fields according to configuration
        $dataFields = $this->analyzeContentService->manageRichTextData(
            $preAnalysisData->getFields(),
            $preAnalysisData->getContentTypeIdentifier(),
            $preAnalysisData->getSiteaccess()
        );
        $preAnalysisData->setFields($dataFields);

        // Retrieving content preview data
        $dataPreviewHtml = $this->previewControllerService->previewContentAction(
            $request,
            $preAnalysisData->getContentId(),
            $preAnalysisData->getVersionNo(),
            $preAnalysisData->getLanguageCode(),
            $preAnalysisData->getSiteaccess()
        )->getContent();
        if (!$dataPreviewHtml || 0 === \strlen($dataPreviewHtml)) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'codein_seo_toolkit.analyzer.error.preview_not_returning_html',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
       
        try {
            $dataContentConfiguration = $this->analyzeContentService->addContentConfigurationToDataArray($data);
        } catch (\Exception $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_BAD_REQUEST,
                'error' => 'codein_seo_toolkit.analyzer.error.content_not_configured',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $preprocessedData = array_merge(
            $preAnalysisData->toArray(), 
            ['previewHtml' => $dataPreviewHtml],
            $dataContentConfiguration
        );

        // validating and creating DTO
        $form = $this->createForm(AnalysisDTOType::class, new AnalysisDTO());
        $form->submit($preprocessedData);
        $result = [];
        if ($form->isValid()) {
            /** @var AnalysisDTO $analysisDTO */
            $analysisDTO = $form->getData();
            $result = $this->analyzeContentService->buildResultObject($analysisDTO);

            if (\array_key_exists('error', $result)) {
                return new JsonResponse(\array_merge($result, [
                    'code' => JsonResponse::HTTP_BAD_REQUEST,
                ]), JsonResponse::HTTP_BAD_REQUEST);
            }

            return new JsonResponse($result);
        }

        return new JsonResponse([
            'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'error' => 'codein_seo_toolkit.analyzer.error.analyzer_form_invalid',
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}

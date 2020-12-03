<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Form\Type\AnalysisDTOType;
use Codein\eZPlatformSeoToolkit\Form\Type\ContentFieldsType;
use Codein\eZPlatformSeoToolkit\Form\Type\RichTextDTOType;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use Codein\eZPlatformSeoToolkit\Model\ContentPreviewDTO;
use Codein\eZPlatformSeoToolkit\Model\RichTextDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzeContentService;
use Doctrine\ORM\EntityManager;
use eZ\Publish\Core\MVC\Symfony\Controller\Content\PreviewController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentController.
 */
final class AnalyzeContentController extends AbstractController
{

    /** @var AnalyzeContentService */
    private $analyzeContentService;


    /** @var PreviewController */
    private $previewControllerService;


    /**
     * AnalyzeContentController constructor.
     */
    public function __construct(
        AnalyzeContentService $analyzeContentService,
        PreviewController $previewControllerService,
        EntityManager $entityManager
    ) {
        $this->analyzeContentService = $analyzeContentService;
        $this->previewControllerService = $previewControllerService;
    }

    public function __invoke(Request $request)
    {
        $data = \json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new HttpException(400, 'Invalid json.');
        }

        // Reorder fields according to configuration
        $data['fields'] = $this->analyzeContentService->manageRichTextData(
            $data['fields'],
            $data['contentTypeIdentifier'],
            $data['siteaccess']
        );

        // Retrieving content preview data
        $dataPreviewHtml = $this->previewControllerService->previewContentAction(
            $request,
            $data['contentId'],
            $data['versionNo'],
            $data['languageCode'],
            $data['siteaccess']
        )->getContent();
        if (!$dataPreviewHtml || 0 === \strlen($dataPreviewHtml)) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'codein_seo_toolkit.analyzer.error.preview_not_returning_html',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        

        $data['previewHtml'] = $dataPreviewHtml;
        try {
            $data = $this->analyzeContentService->addContentConfigurationToDataArray($data);
        }
        catch (\Exception $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_BAD_REQUEST,
                'error' => 'codein_seo_toolkit.analyzer.error.content_not_configured'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // validating and creating DTO
        $form = $this->createForm(AnalysisDTOType::class, new AnalysisDTO());
        $form->submit($data);
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

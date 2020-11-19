<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;
use Codein\eZPlatformSeoToolkit\Form\Type\ContentFieldsType;
use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use Codein\eZPlatformSeoToolkit\Service\AnalyzeContentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentController.
 */
final class AnalyzeContentController extends AbstractController
{
    private $analyzeContentService;

    /**
     * AnalyzeContentController constructor.
     */
    public function __construct(
        AnalyzeContentService $analyzeContentService
    ) {
        $this->analyzeContentService = $analyzeContentService;
    }

    public function __invoke(Request $request)
    {
        $data = \json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new HttpException(400, 'Invalid json.');
        }

        $form = $this->createForm(ContentFieldsType::class, new ContentFields());
        $form->submit($data);
        $result = [];
        if ($form->isValid()) {
            /** @var ContentFields $contentFields */
            $contentFields = $form->getData();
            $result = $this->analyzeContentService->buildResultObject($request, $contentFields);
        }

        return new JsonResponse($result);
    }
}

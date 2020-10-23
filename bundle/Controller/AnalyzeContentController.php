<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Form\Type\ContentFieldsType;
use Codein\eZPlatformSeoToolkit\Helper\XmlValidator;
use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentController.
 */
class AnalyzeContentController extends AbstractController
{
    protected $richTextAnalyzer;
    protected $contentTypeService;

    /**
     * AnalyzeContentController constructor.
     */
    public function __construct(RichTextParentAnalyzerService $richTextAnalyzer, ContentTypeService $contentTypeService)
    {
        $this->richTextAnalyzer = $richTextAnalyzer;
        $this->contentTypeService = $contentTypeService;
    }

    public function __invoke(Request $request)
    {
        $data = \json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new HttpException(400, 'Invalid json');
        }

        $form = $this->createForm(ContentFieldsType::class, new ContentFields());
        $form->submit($data);
        if ($form->isValid()) {
            $result = [];
            /** @var ContentFields $contentFields */
            $contentFields = $form->getData();
            $contentType = $this->contentTypeService->loadContentType($contentFields->getContentTypeIdentifier());

            foreach ($contentFields->getFields() as $field) {
                $fieldDefinition = $contentType->getFieldDefinition($field->getFieldIdentifier());
                $fieldDefinition = ($fieldDefinition) ?? new FieldDefinition(
                    [
                        'fieldTypeIdentifier' => 'ezrichtext',
                    ]
                );
                if (false === XmlValidator::isXMLContentValid($field->getFieldValue())) {
                    throw new HttpException(400, \sprintf('Invalid xml value for field "%s"', $field->getFieldIdentifier()));
                }
                $fieldValue = new Value($field->getFieldValue());
                $result[$field->getFieldIdentifier()] = $this->richTextAnalyzer
                    ->analyze($fieldDefinition, $fieldValue);
            }

            return $result;
        }

        return ['form' => $form];
    }
}

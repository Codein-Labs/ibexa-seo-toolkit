<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

use Codein\eZPlatformSeoToolkit\Analyzer\ContentPreviewParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Doctrine\ORM\EntityManager;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value;
use Codein\eZPlatformSeoToolkit\Helper\XmlValidator;
use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentService.
 */
final class AnalyzeContentService
{

    /** @var EntityManager $em */
    private $em;

    /** @var ContentTypeService $contentTypeService*/
    private $contentTypeService;

    /** @var ContentPreviewParentAnalyzerService $contentPreviewAnalyzer */
    private $contentPreviewAnalyzer;

    /** @var RichTextParentAnalyzerService $richTextAnalyzer */
    private $richTextAnalyzer;

    public function __construct(
        EntityManager $em, 
        ContentTypeService $contentTypeService, 
        RichTextParentAnalyzerService $richTextAnalyzer,
        ContentPreviewParentAnalyzerService $contentPreviewAnalyzer
    ) {
        $this->em = $em;
        $this->contentTypeService = $contentTypeService;
        $this->richTextAnalyzer = $richTextAnalyzer;
        $this->contentPreviewAnalyzer = $contentPreviewAnalyzer;
    }

    public function buildResultObject(Request $request, ContentFields $contentFields) {
        $contentType = $this->contentTypeService->loadContentTypeByIdentifier($contentFields->getContentTypeIdentifier());
        $contentId = $contentFields->getContentId();

        $result = [];

        $data = $this->em->getRepository(ContentConfiguration::class)->findOneBy([
            'contentId' => $contentId
        ]);
        
        if ($data) {
            $data = $data->toArray();
        }
        else {
            return ['error' => 'codein_seo_toolkit.analyzer.error.content_not_configured'];
        }
        
        $data = \array_merge($data, $contentFields->toArray());
        $data['request'] = $request;
        foreach ($contentFields->getFields() as $field) {
            $fieldDefinition = $contentType->getFieldDefinition($field->getFieldIdentifier());
            $fieldDefinition = ($fieldDefinition) ?? new FieldDefinition(
                [
                    'fieldTypeIdentifier' => 'ezrichtext',
                ]
            );
            if (false === XmlValidator::isXMLContentValid($field->getFieldValue())) {
                throw new HttpException(400, \sprintf('Invalid xml value for field "%s".', $field->getFieldIdentifier()));
            }
            $fieldValue = new Value($field->getFieldValue());
            $result = $this->richTextAnalyzer
                ->analyze($fieldDefinition, $fieldValue, $data);
            
        }
        $resultContentPreview = $this->contentPreviewAnalyzer->analyze($data);
        $result = \array_merge_recursive($result, $resultContentPreview);

        return $result;
    }
}

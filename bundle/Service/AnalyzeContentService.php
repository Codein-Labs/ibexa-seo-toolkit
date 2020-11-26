<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

use Codein\eZPlatformSeoToolkit\Analyzer\ContentPreviewParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Analyzer\RichTextParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Doctrine\ORM\EntityManager;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value;
use Codein\eZPlatformSeoToolkit\Helper\XmlValidator;
use Codein\eZPlatformSeoToolkit\Model\ContentFields;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentService.
 */
final class AnalyzeContentService
{

    /** @var EntityManager $em */
    private $em;

    /** @var LoggerInterface $logger */
    private $logger;

    /** @var ContentTypeService $contentTypeService*/
    private $contentTypeService;

    /** @var ContentPreviewParentAnalyzerService $contentPreviewAnalyzer */
    private $contentPreviewAnalyzer;

    /** @var RichTextParentAnalyzerService $richTextAnalyzer */
    private $richTextAnalyzer;

    /** @var SiteAccessConfigResolver $siteAccessConfigResolver */
    private $siteAccessConfigResolver;


    public function __construct(
        EntityManager $em, 
        LoggerInterface $logger,
        ContentTypeService $contentTypeService, 
        RichTextParentAnalyzerService $richTextAnalyzer,
        ContentPreviewParentAnalyzerService $contentPreviewAnalyzer,
        SiteAccessConfigResolver $siteAccessConfigResolver
    ) {
        $this->em = $em;
        $this->logger = $logger;
        $this->contentTypeService = $contentTypeService;
        $this->richTextAnalyzer = $richTextAnalyzer;
        $this->contentPreviewAnalyzer = $contentPreviewAnalyzer;
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    /**
     * Launch analysis
     *
     * @param Request $request
     * @param ContentFields $contentFields
     * @return array
     * 
     * @throws HttpException 400 if xmlvalue field is invalid
     */
    public function buildResultObject(Request $request, ContentFields $contentFields) {
        $contentType = $this->contentTypeService->loadContentTypeByIdentifier($contentFields->getContentTypeIdentifier());
        
        $result = [];

        $data = $this->createAnalysisDataArray($request, $contentFields);
        if (array_key_exists('error', $data))
        {
            return $data;
        }

        foreach ($contentFields->getFields() as $field) {
            $richTextFieldConfigured = $this->getRichtextFieldConfiguredForContentType(
                $contentFields->getContentTypeIdentifier(),
                $contentFields->getSiteaccess()
            );
            // We only support one rich text field for now
            if ($richTextFieldConfigured !== $field->getFieldIdentifier()) {
                continue;
            }
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

    /**
     * Get and aggregate data needed for analysis
     *
     * @param Request $request
     * @param ContentFields $contentFields
     * @return array
     */
    public function createAnalysisDataArray($request, $contentFields)
    {
        $contentId = $contentFields->getContentId();

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

        return $data;
    }

    /**
     * Get richtext field identifier configured for the provided content type
     *
     * @param string $contentTypeIdentifier
     * @param string $siteaccess
     * @return string
     */
    public function getRichtextFieldConfiguredForContentType(string $contentTypeIdentifier, string $siteaccess): string
    {
        try {
            return $this->siteAccessConfigResolver->getParameterConfig(
                'analysis',
                $siteaccess
            )['content_types'][$contentTypeIdentifier]['richtext_field'];
        }
        catch (\Exception $e) {
            $this->logger->warning('Analyzer config is not set correctly for this content type: '.$contentTypeIdentifier);
            return "";
        }
    }

}

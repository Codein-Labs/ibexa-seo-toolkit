<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

use Codein\eZPlatformSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\eZPlatformSeoToolkit\Helper\XmlValidator;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Doctrine\ORM\EntityManager;
use eZ\Publish\API\Repository\ContentTypeService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentService.
 */
final class AnalyzeContentService
{
    /** @var EntityManager */
    private $em;

    /** @var LoggerInterface */
    private $logger;

    /** @var ContentTypeService */
    private $contentTypeService;

    /** @var ParentAnalyzerService */
    private $analyzer;

    /** @var SiteAccessConfigResolver */
    private $siteAccessConfigResolver;

    public function __construct(
        EntityManager $em,
        LoggerInterface $logger,
        ContentTypeService $contentTypeService,
        ParentAnalyzerService $analyzer,
        SiteAccessConfigResolver $siteAccessConfigResolver
    ) {
        $this->em = $em;
        $this->logger = $logger;
        $this->contentTypeService = $contentTypeService;
        $this->analyzer = $analyzer;
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    /**
     * Launch analysis.
     *
     * @throws HttpException 400 if xmlvalue field is invalid
     * @return array
     */
    public function buildResultObject(AnalysisDTO $analysisData)
    {
        $result = $this->analyzer
            ->analyze($analysisData);

        return $result;
    }

    /**
     * Get richtext field identifier configured for the provided content type.
     */
    public function getRichtextFieldConfiguredForContentType(string $contentTypeIdentifier, string $siteaccess): array
    {
        try {
            return $this->siteAccessConfigResolver->getParameterConfig(
                'analysis',
                $siteaccess
            )['content_types'][$contentTypeIdentifier]['richtext_fields'];
        } catch (\Exception $e) {
            $this->logger->warning('Analyzer config is not set correctly for this content type: ' . $contentTypeIdentifier);

            return [];
        }
    }

    public function loadContentConfiguration(string $contentId, string $languageCode): ?ContentConfiguration
    {
        return $this->em->getRepository(ContentConfiguration::class)->findOneBy([
            'contentId' => $contentId,
            'languageCode' => $languageCode,
        ]);
    }

    /**
     * Checks configuration and reorder/.
     *
     * @param array $richTextFieldData
     */
    public function manageRichTextData($richTextFieldsData, string $contentTypeIdentifier, string $siteaccess): ?array
    {
        $newRichTextFieldData = [];
        $richTextFieldsConfigured = $this->getRichtextFieldConfiguredForContentType($contentTypeIdentifier, $siteaccess);

        foreach ($richTextFieldsConfigured as $richTextFieldConfigured) {
            $position = $this->richTextFieldPosition($richTextFieldsData, $richTextFieldConfigured);
            if (-1 !== $position) {
                if (!XmlValidator::isXMLContentValid($richTextFieldsData[$position]->getFieldValue())) {
                    $this->logger->warning('Rich text field configured "' . $richTextFieldConfigured . '" has invalid XML content');
                    continue;
                }
                $newRichTextFieldData[] = $richTextFieldsData[$position];
            } else {
                $this->logger->warning('Rich text field configured "' . $richTextFieldConfigured . '" does not appear in request data');
            }
        }

        return $newRichTextFieldData;
    }

    /**
     * Get and aggregate data needed for analysis.
     *
     * @param array $contentFields
     * @param mixed $data
     * @return array
     */
    public function addContentConfigurationToDataArray($data)
    {
        if (!\array_key_exists('contentId', $data) || !\array_key_exists('languageCode', $data)) {
            return [];
        }

        $contentId = $data['contentId'];
        $languageCode = $data['languageCode'];

        $contentConfiguration = $this->loadContentConfiguration($contentId, $languageCode);

        if ($contentConfiguration) {
            $contentConfiguration = $contentConfiguration->toArray();
        } else {
            throw new \Exception('Content not configured');
        }

        $data = \array_merge($contentConfiguration, $data);

        return $data;
    }

    private function richTextFieldPosition($richTextFieldsData, $richTextField): int
    {
        foreach ($richTextFieldsData as $key => $someRichTextField) {
            if ($someRichTextField->getFieldIdentifier() === $richTextField) {
                return $key;
                break;
            }
        }

        return -1;
    }
}

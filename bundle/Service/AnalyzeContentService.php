<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

use Codein\eZPlatformSeoToolkit\Analysis\ParentAnalyzerInterface;
use Codein\eZPlatformSeoToolkit\Analysis\ParentAnalyzerService;
use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\eZPlatformSeoToolkit\Helper\XmlValidator;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AnalyzeContentService.
 */
final class AnalyzeContentService
{
    private const CONTENT_ID = 'contentId';
    private const LANGUAGE_CODE = 'languageCode';

    private $logger;
    private $entityManager;
    private $parentAnalyzerService;
    private $siteAccessConfigResolver;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        ParentAnalyzerInterface $parentAnalyzer,
        SiteAccessConfigResolver $siteAccessConfigResolver
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->parentAnalyzerService = $parentAnalyzer;
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    /**
     * Launch analysis.
     *
     * @throws HttpException 400 if xmlValue field is invalid
     * @return array
     */
    public function buildResultObject(AnalysisDTO $analysisDTO)
    {
        return $this->parentAnalyzerService
            ->analyze($analysisDTO);
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
        return $this->entityManager->getRepository(ContentConfiguration::class)->findOneBy([
            self::CONTENT_ID => $contentId,
            self::LANGUAGE_CODE => $languageCode,
        ]);
    }

    /**
     * Checks configuration and reorder/.
     */
    public function manageRichTextData(array $richTextFieldsData, string $contentTypeIdentifier, string $siteaccess): ?array
    {
        $newRichTextFieldData = [];
        $richTextFieldsConfigured = $this->getRichtextFieldConfiguredForContentType($contentTypeIdentifier, $siteaccess);

        foreach ($richTextFieldsConfigured as $richTextFieldConfigured) {
            $position = $this->richTextFieldPosition($richTextFieldsData, $richTextFieldConfigured);
            if (-1 !== $position) {
                if (!XmlValidator::isXMLContentValid($richTextFieldsData[$position]['fieldValue'])) {
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
        if (!\array_key_exists(self::CONTENT_ID, $data) || !\array_key_exists(self::LANGUAGE_CODE, $data)) {
            return [];
        }

        $contentId = $data[self::CONTENT_ID];
        $languageCode = $data[self::LANGUAGE_CODE];

        $contentConfiguration = $this->loadContentConfiguration($contentId, $languageCode);

        if ($contentConfiguration) {
            $contentConfiguration = $contentConfiguration->toArray();
        } else {
            throw new \Exception('Content not configured');
        }

        return \array_merge($contentConfiguration, $data);
    }

    private function richTextFieldPosition($richTextFieldsData, $richTextField): int
    {
        foreach ($richTextFieldsData as $key => $someRichTextField) {
            if ($someRichTextField['fieldIdentifier'] === $richTextField) {
                return $key;
            }
        }

        return -1;
    }
}

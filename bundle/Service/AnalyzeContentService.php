<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Service;

use Codein\IbexaSeoToolkit\Analysis\ParentAnalyzerInterface;
use Codein\IbexaSeoToolkit\Entity\ContentConfiguration;
use Codein\IbexaSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\IbexaSeoToolkit\Helper\XmlValidator;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AnalyzeContentService.
 */
final class AnalyzeContentService
{
    private const CONTENT_ID = 'contentId';
    private const LANGUAGE_CODE = 'languageCode';
    private const NOT_FOUND_FIELD_POSITION = -1;

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
     * Get richText field identifier configured for the provided content type.
     */
    public function getRichtextFieldConfiguredForContentType(string $contentTypeIdentifier, string $siteaccess): array
    {
        $contentTypes = $this->siteAccessConfigResolver->getParameterConfig(
            'analysis',
            $siteaccess
        )['content_types'];

        return $contentTypes[$contentTypeIdentifier]['richtext_fields'];
    }

    /**
     * Checks configuration and reorder.
     */
    public function manageRichTextDataFields(array $richTextFieldsData, string $contentTypeIdentifier, string $siteaccess): ?array
    {
        $newRichTextFieldData = [];
        $richTextFieldsConfigured = $this->getRichtextFieldConfiguredForContentType($contentTypeIdentifier, $siteaccess);

        foreach ($richTextFieldsConfigured as $richTextFieldConfigured) {
            $position = $this->richTextFieldPosition($richTextFieldsData, $richTextFieldConfigured);
            if (self::NOT_FOUND_FIELD_POSITION !== $position) {
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
     */
    public function addContentConfigurationToDataArray(AnalysisDTO $preAnalysisData): ?ContentConfiguration
    {
        if (!$preAnalysisData->getContentId() || !$preAnalysisData->getLanguageCode()) {
            return null;
        }

        /** @var ContentConfiguration $contentConfiguration */
        $contentConfiguration = $this->entityManager->getRepository(ContentConfiguration::class)->findOneBy([
            self::CONTENT_ID => $preAnalysisData->getContentId(),
            self::LANGUAGE_CODE => $preAnalysisData->getLanguageCode(),
        ]);
        if (!$contentConfiguration) {
            throw new \Exception('Content is not already configured!');
        }

        return $contentConfiguration;
    }

    private function richTextFieldPosition($richTextFieldsData, $richTextField): int
    {
        foreach ($richTextFieldsData as $key => $someRichTextField) {
            if ($someRichTextField->getFieldIdentifier() === $richTextField) {
                return $key;
            }
        }

        return self::NOT_FOUND_FIELD_POSITION;
    }
}

<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Controller;

use Codein\IbexaSeoToolkit\Controller\Annotations as Rest;
use Codein\IbexaSeoToolkit\Entity\ContentConfiguration;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GetContentConfigurationController.
 * @Rest\View()
 */
final class GetContentConfigurationController
{
    private $entityManager;

    /**
     * ContentConfigurationController constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(int $contentId)
    {
        return $this->entityManager->getRepository(ContentConfiguration::class)->findOneBy([
            'contentId' => $contentId,
        ]) ?: (new ContentConfiguration())->toArray();
    }
}

<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Codein\eZPlatformSeoToolkit\Form\Type\ContentConfigurationType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UpdateContentConfigurationController.
 */
final class UpdateContentConfigurationController
{
    private $formFactory;
    private $entityManager;

    /**
     * ContentConfigurationController constructor.
     * @Rest\View()
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function __invoke(int $contentId, Request $request)
    {
        $contentConfiguration = $this->entityManager->getRepository(ContentConfiguration::class)
            ->findOneBy(['contentId' => $contentId]) ?: (new ContentConfiguration());
        $form = $this->formFactory->create(ContentConfigurationType::class, $contentConfiguration);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->entityManager->persist($contentConfiguration);
            $this->entityManager->flush();

            return $contentConfiguration->getId()
                ? ['message' => 'updated', 'code' => Response::HTTP_OK]
                : ['message' => 'created', 'code' => Response::HTTP_CREATED]
            ;
        }

        return $form;
    }
}

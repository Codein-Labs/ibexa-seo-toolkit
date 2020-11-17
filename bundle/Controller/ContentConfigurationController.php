<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\eZPlatformSeoToolkit\Entity\ContentConfiguration;
use Codein\eZPlatformSeoToolkit\Form\Type\ContentConfigurationType;
use Doctrine\ORM\EntityManager;
use eZ\Publish\Core\Repository\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ContentConfigurationController.
 */
class ContentConfigurationController extends Controller
{
    /** @var SiteAccessConfigResolver $siteAccessConfigResolver */
    protected $siteAccessConfigResolver;

    /** @var EntityManager $em */
    protected $em;

    /** @var NotificationService $notificationService */
    protected $notificationService;

    /**
     * ContentConfigurationController constructor.
     */
    public function __construct(SiteAccessConfigResolver $siteAccessConfigResolver, EntityManager $em, NotificationService $notificationService)
    {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
        $this->em = $em;
    }

    public function getAction(Request $request)
    {
        $data = $request->query->all();

        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new HttpException(400, 'Invalid json.');
        }

        $form = $this->createForm(ContentConfigurationType::class, new ContentConfiguration());
        $form->submit($data);
        if ($form->isValid()) {
            $result = [];
            /** @var ContentConfiguration $contentConfiguration */
            $contentConfigurationData = $form->getData();
            $result = $this->em->getRepository(ContentConfiguration::class)->findOneBy([
                'contentId' => $contentConfigurationData->getContentId()
            ]);
            /**
             * @TODO
             */

            return $result ?: [];
        }

        return ['form' => $form];
    }

    public function updateAction(Request $request)
    {
        $data = \json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new HttpException(400, 'Invalid json.');
        }

        $form = $this->createForm(ContentConfigurationType::class, new ContentConfiguration());
        $form->submit($data);
        if ($form->isValid()) {
            $result = [];
            /** @var ContentConfiguration $contentConfiguration */
            $contentConfiguration = $form->getData();

            $existingContentConfiguration = $this->em->getRepository(ContentConfiguration::class)
                ->findOneBy(['contentId' => $contentConfiguration->getContentId()]);
            if ($existingContentConfiguration) {
                /** @var ContentConfiguration $existingContentConfiguration */
                $existingContentConfiguration->setKeyword($contentConfiguration->getKeyword());
                $existingContentConfiguration->setIsPillarContent($contentConfiguration->getIsPillarContent());
                $this->em->persist($existingContentConfiguration);

                $result = ['action' => 'updated'];
            }
            else {
                $this->em->persist($contentConfiguration);
                $result = ['action' => 'created'];
            }
            $this->em->flush();
            /**
             * @TODO
             */

            return $result;
        }
    }
}

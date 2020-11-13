<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Controller;

use Codein\eZPlatformSeoToolkit\Helper\SiteAccessConfigResolver;
use Codein\eZPlatformSeoToolkit\Model\ContentConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ContentConfigurationController.
 */
class ContentConfigurationController extends AbstractController
{
    protected $siteAccessConfigResolver;

    /**
     * ContentConfigurationController constructor.
     */
    public function __construct(SiteAccessConfigResolver $siteAccessConfigResolver)
    {
        $this->siteAccessConfigResolver = $siteAccessConfigResolver;
    }

    public function __invoke(Request $request)
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

            /**
             * @TODO
             */

            return $result;
        }

        return ['form' => $form];
    }
}

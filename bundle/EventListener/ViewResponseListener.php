<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\EventListener;

use Codein\IbexaSeoToolkit\Controller\Annotations\View as ViewAnnotation;
use Codein\IbexaSeoToolkit\Model\View;
use Codein\IbexaSeoToolkit\Service\ViewHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ViewResponseListener implements EventSubscriberInterface
{
    public $viewHandler;

    public function __construct(ViewHandler $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        $configuration = $request->attributes->get('_template');

        if (!$configuration instanceof ViewAnnotation) {
            return;
        }

        $view = $event->getControllerResult();
        if (!$view instanceof View) {
            $view = new View($view);
        }
        if (null !== $configuration->getStatusCode() && (null === $view->getStatusCode() || Response::HTTP_OK === $view->getStatusCode())) {
            $view->setStatusCode($configuration->getStatusCode());
        }

        $response = $this->viewHandler->handle($view, $request);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        // Must be executed before SensioFrameworkExtraBundle's listener
        return [
            KernelEvents::VIEW => ['onKernelView', 30],
        ];
    }
}

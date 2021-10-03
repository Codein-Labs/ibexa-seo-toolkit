<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Service;

use Codein\IbexaSeoToolkit\Model\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * The View class takes care of encoding your data in json via the symfony serializer.
 */
class ViewHandler implements ViewHandlerInterface
{
    private $serializer;
    private $requestStack;

    public function __construct(
        SerializerInterface $serializer,
        RequestStack $requestStack
    ) {
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
    }

    public function handle(View $view, Request $request = null): Response
    {
        if (null === $request) {
            $request = $this->requestStack->getCurrentRequest();
        }

        $format = $view->getFormat() ?: $request->getRequestFormat();

        if (!$this->supports($format)) {
            $msg = "Format '$format' not supported, handler must be implemented";

            throw new UnsupportedMediaTypeHttpException($msg);
        }

        return $this->createResponse($view, $request, $format);
    }

    public function createResponse(View $view, Request $request, string $format): Response
    {
        $response = $this->initResponse($view, $format);

        if (!$response->headers->has('Content-Type')) {
            $mimeType = $request->attributes->get('media_type');
            if (null === $mimeType) {
                $mimeType = $request->getMimeType($format);
            }

            $response->headers->set('Content-Type', $mimeType);
        }

        return $response;
    }

    public function supports(string $format): bool
    {
        return 'json' === $format;
    }

    /**
     * Gets a response HTTP status code from a View instance.
     *
     * @param string|false|null
     * @param mixed|null $content
     */
    private function getStatusCode(View $view, $content = null): int
    {
        $form = $this->getFormFromView($view);

        if (null !== $form && $form->isSubmitted() && !$form->isValid()) {
            return Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        $statusCode = $view->getStatusCode();
        if (null !== $statusCode) {
            return $statusCode;
        }

        return null !== $content ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
    }

    private function initResponse(View $view, string $format): Response
    {
        $content = null;
        if (null !== $view->getData()) {
            $data = ($this->getFormFromView($view)) ?? $view->getData();

            $content = $this->serializer->serialize($data, $format, []);
        }

        $response = $view->getResponse();
        $response->setStatusCode($this->getStatusCode($view, $content));

        if (null !== $content) {
            $response->setContent($content);
        }

        return $response;
    }

    private function getFormFromView(View $view): ?FormInterface
    {
        $data = $view->getData();

        if ($data instanceof FormInterface) {
            return $data;
        }

        if (\is_array($data) && isset($data['form']) && $data['form'] instanceof FormInterface) {
            return $data['form'];
        }

        return null;
    }

    private function getDataFromView(View $view)
    {
        $form = $this->getFormFromView($view);

        if (null === $form) {
            return $view->getData();
        }

        return $form;
    }
}

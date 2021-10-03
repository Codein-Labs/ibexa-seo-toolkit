<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Service;

use Codein\IbexaSeoToolkit\Model\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

/**
 * interface ViewHandlerInterface.
 */
interface ViewHandlerInterface
{
    /**
     * Handles a request with the proper handler.
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function handle(View $view, Request $request = null): Response;

    public function supports(string $format): bool;
}

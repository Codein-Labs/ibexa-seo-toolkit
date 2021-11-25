<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Model;

use Symfony\Component\HttpFoundation\Response;

/**
 * Default View implementation.
 */
class View
{
    private $data;
    private $statusCode;
    private $format;
    private $response;

    public function __construct($data = null, ?int $statusCode = null, array $headers = [])
    {
        $this->setData($data);
        $this->setStatusCode($statusCode);

        if (!empty($headers)) {
            $this->getResponse()->headers->replace($headers);
        }
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->getResponse()->headers->set($name, $value);

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->getResponse()->headers->replace($headers);

        return $this;
    }

    public function setStatusCode(?int $code): self
    {
        if (null !== $code) {
            $this->statusCode = $code;
        }

        return $this;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->getResponse()->headers->all();
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getResponse(): Response
    {
        if (null === $this->response) {
            $this->response = new Response();

            if (null !== ($code = $this->getStatusCode())) {
                $this->response->setStatusCode($code);
            }
        }

        return $this->response;
    }
}

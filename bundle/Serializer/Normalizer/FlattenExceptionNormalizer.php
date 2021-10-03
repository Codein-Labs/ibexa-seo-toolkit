<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Serializer\Normalizer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class FlattenExceptionNormalizer implements NormalizerInterface
{
    public function normalize($exception, $format = null, array $context = [])
    {
        return [
            'code' => $exception->getStatusCode(),
            'message' => $exception->getMessage(),
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FlattenException;
    }
}

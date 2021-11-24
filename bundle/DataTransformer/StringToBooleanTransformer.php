<?php

namespace Codein\IbexaSeoToolkit\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToBooleanTransformer implements DataTransformerInterface
{

    /** @var string */
    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function transform($value)
    {
        $value[$this->key] = (bool)$value[$this->key];
        return $value;
    }

    public function reverseTransform($value)
    {
        $value[$this->key] = (string)$value[$this->key];
        return $value;
    }
}

<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Exception;

/**
 * Class ValidationException.
 */
class ValidationException extends \RuntimeException
{
    /**
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     */
    public function __construct($message = null, \Exception $previous = null)
    {
        parent::__construct($message ?: $this->__toString(), 400, $previous);
    }
}

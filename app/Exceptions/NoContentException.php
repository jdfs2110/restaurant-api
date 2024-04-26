<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción que se lanza cuando una lista está vacía
 */
class NoContentException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

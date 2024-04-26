<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción que se lanza cuando la cantidad del stock es negativa, luego de restarle X cantidad
 */
class NegativeQuantityException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción que se lanza cuando el usuario no tiene el rol 'mesero'
 */
class UserIsNotWaiterException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

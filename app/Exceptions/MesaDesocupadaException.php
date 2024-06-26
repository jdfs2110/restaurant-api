<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción que se lanza cuando una mesa no tiene el estado 'ocupada'
 */
class MesaDesocupadaException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

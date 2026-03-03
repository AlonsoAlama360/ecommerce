<?php

namespace App\Domain\User\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $message = 'Las credenciales no coinciden con nuestros registros.';
}

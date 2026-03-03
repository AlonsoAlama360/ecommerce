<?php

namespace App\Domain\User\Exceptions;

use Exception;

class UserInactiveException extends Exception
{
    protected $message = 'Tu cuenta está desactivada. Contacta al administrador.';
}

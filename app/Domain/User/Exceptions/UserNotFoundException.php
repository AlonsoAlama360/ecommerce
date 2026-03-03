<?php

namespace App\Domain\User\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'Usuario no encontrado.';
}

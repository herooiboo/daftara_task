<?php

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

class UserAlreadyExistsException extends Exception
{
    protected $message = 'A user with this email already exists.';
}

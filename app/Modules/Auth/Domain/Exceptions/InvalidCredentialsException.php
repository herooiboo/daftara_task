<?php

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $message = 'Invalid credentials provided.';
}

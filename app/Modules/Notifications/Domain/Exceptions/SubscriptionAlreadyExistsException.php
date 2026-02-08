<?php

namespace App\Modules\Notifications\Domain\Exceptions;

use Exception;

class SubscriptionAlreadyExistsException extends Exception
{
    protected $message = 'User is already subscribed to this warehouse.';
}

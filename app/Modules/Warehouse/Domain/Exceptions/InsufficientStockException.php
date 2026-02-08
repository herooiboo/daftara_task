<?php

namespace App\Modules\Warehouse\Domain\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    protected $message = 'Insufficient stock in source warehouse.';
}

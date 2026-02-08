<?php

namespace App\Modules\Warehouse\Domain\Exceptions;

use Exception;

class WarehouseNotFoundException extends Exception
{
    protected $message = 'Warehouse not found.';
}

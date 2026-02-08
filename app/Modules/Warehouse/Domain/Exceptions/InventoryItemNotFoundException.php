<?php

namespace App\Modules\Warehouse\Domain\Exceptions;

use Exception;

class InventoryItemNotFoundException extends Exception
{
    protected $message = 'Inventory item not found.';
}

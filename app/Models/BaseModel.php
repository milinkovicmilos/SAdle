<?php

namespace App\Models;

use App\Config\DBHandler;

abstract class BaseModel
{
    protected readonly DBHandler $dbc;

    public function __construct()
    {
        $this->dbc = new DBHandler();
    }
}

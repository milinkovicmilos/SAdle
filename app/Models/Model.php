<?php

namespace App\Models;

use App\Config\DBHandler;

abstract class Model
{
    protected readonly DBHandler $dbc;

    public function __construct()
    {
        $this->dbc = new DBHandler();
    }
}

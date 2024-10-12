<?php

namespace App\Models;

class Vehicle extends BaseModel
{
    public function getVehicles(): array
    {
        return $this->dbc->query('SELECT category_id, name FROM vehicles');
    }
}

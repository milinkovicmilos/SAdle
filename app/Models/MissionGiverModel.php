<?php

namespace App\Models;

class MissionGiverModel extends Model
{
    public function retrieveGiverName(int $giverId): string
    {
        return $this->dbc->fetchPrepared(
            "SELECT name FROM mission_givers WHERE id = ?",
            [$giverId]
        )[0]->name;
    }
}

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

    public function retrieveMissionGivers(): array
    {
        return $this->dbc->fetchPrepared("SELECT id, name FROM mission_givers", []);
    }
}

<?php

namespace App\Models;

class MissionOriginModel extends Model
{
    public function retrieveOriginName($originId): string
    {
        return $this->dbc->fetchPrepared(
            "SELECT name FROM mission_origins WHERE id = ?",
            [$originId]
        )[0]->name;
    }

    public function retrieveMissionOrigins(): array
    {
        return $this->dbc->fetchPrepared("SELECT id, name FROM mission_origins", []);
    }
}

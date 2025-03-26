<?php

namespace App\Models;

class MissionModel extends Model
{
    // Retrieves the value of desired column
    public function retrieveMissionInfo($name, $missionId): string
    {
        if ($name == "giver" || $name == "origin") {
            $name = $name . "_id";
        }

        $value = $this->dbc->fetchPrepared("SELECT $name as value FROM missions WHERE id = ?", [$missionId])[0]->value;

        switch ($name) {
            case "origin_id":
                $missionOriginModel = new MissionOriginModel();
                return $missionOriginModel->retrieveOriginName($value);

            case "giver_id":
                $missionGiverModel = new MissionGiverModel();
                return $missionGiverModel->retrieveGiverName($value);
        }
        return $value;
    }

    public function retrieveMissionTitles(): array
    {
        return $this->dbc->fetchPrepared("SELECT id, title FROM missions", []);
    }

    public function retrieveMissionOriginId(int $missionId): int
    {
        return $this->dbc->fetchPrepared(
            "SELECT origin_id as id FROM missions WHERE id = ?",
            [$missionId]
        )[0]->id;
    }

    public function retrieveMissionGiverId(int $missionId): int
    {
        return $this->dbc->fetchPrepared(
            "SELECT giver_id as id FROM missions WHERE id = ?",
            [$missionId]
        )[0]->id;
    }

    public function retrieveAllMissionAttributes(int $missionId): object
    {
        return $this->dbc->fetchPrepared(
            <<<SQL
                SELECT
                    title,
                    description,
                    objective,
                    (
                        SELECT name FROM mission_origins WHERE id = m.origin_id
                    ) as origin,
                    (
                        SELECT name FROM mission_givers WHERE id = m.giver_id
                    ) as giver,
                    reward
                FROM missions m
                WHERE id = ?;
            SQL,
            [$missionId]
        )[0];
    }
}

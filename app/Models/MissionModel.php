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
}

<?php

namespace App\Models;

class GameModel extends Model
{
    public function retrieveCurrentDate(): string
    {
        return date("Y-m-d");
    }

    public function retrieveSecondsToNextDay(): int
    {
        return strtotime("tomorrow") - time();
    }

    public function retrieveCurrentGameId(): int
    {
        return $this->dbc->fetchPrepared(
            "SELECT id FROM games WHERE game_date = ?",
            [$this->retrieveCurrentDate()]
        )[0]->id;
    }

    public function retrieveActiveSongId(): int
    {
        // Get todays song id
        $result = $this->dbc->fetchPrepared(
            "SELECT song_id FROM games WHERE game_date = ?",
            [$this->retrieveCurrentDate()]
        )[0]->song_id;

        if (is_null($result))
            throw new \Exception("Radio game song id for today is null.");

        return $result;
    }

    public function retrieveCurrentMissionId(string $attributeType): int
    {
        $columnName = $attributeType . "_mission_id";
        return $this->dbc->fetchPrepared(
            "SELECT $columnName as id FROM games WHERE game_date = ?",
            [$this->retrieveCurrentDate()]
        )[0]->id;
    }
}

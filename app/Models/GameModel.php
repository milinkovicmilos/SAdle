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

    public function retrieveCurrentMissionId($missionNumber): mixed
    {
        $map = [
            1 => "title_mission_id",
            2 => "origin_mission_id",
            3 => "giver_mission_id",
        ];
        return $this->dbc->fetchPrepared(
            "SELECT $map[$missionNumber] as id FROM games WHERE game_date = ?",
            [$this->retrieveCurrentDate()]
        )[0]->id;
    }
}

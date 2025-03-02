<?php

namespace App\Models;

class GameModel extends Model
{
    public function retrieveCurrentDate(): string
    {
        date_default_timezone_set('Asia/Dhaka');
        return date("Y-m-d");
    }

    public function retrieveSecondsToNextDay(): int
    {
        return strtotime("tomorrow") - time();
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

    public function retrieveCorrectRadioId(): int
    {
        $result = $this->dbc->fetchPrepared(
            "SELECT radio_id FROM games WHERE game_date = ?",
            [$this->retrieveCurrentDate()]
        )[0]->radio_id;

        if (is_null($result))
            throw new \Exception("Correct radio for todays game is null.");

        return $result;
    }
}

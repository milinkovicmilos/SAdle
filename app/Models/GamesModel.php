<?php

namespace App\Models;

class GamesModel extends Model
{
    public function retrieveActiveSongId(): int
    {
        // Get todays song id
        $currentDate = date("Y-m-d");
        $result = $this->dbc->fetchPrepared("SELECT song_id FROM games WHERE game_date = ?", [$currentDate])[0]->song_id;
        if (is_null($result))
            throw new \Exception("Radio game song id for today is null.");

        return $result;
    }
}

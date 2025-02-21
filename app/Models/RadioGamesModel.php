<?php

namespace App\Models;

class RadioGamesModel extends Model
{
    public function retrieveActiveSongId(): int
    {
        // Get todays song id
        $currentDate = date("Y-m-d");
        return $this->dbc->fetchPrepared("SELECT song_id FROM radio_games WHERE game_date = ?", [$currentDate])[0]->song_id;
    }
}

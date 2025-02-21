<?php

namespace App\Models;

use App\Models\RadioGamesModel;

class RadioModel extends Model
{
    public function retrieveRadioStations(): array
    {
        return $this->dbc->selectAll("radio_stations");
    }

    public function retrieveRadioSongName(): string
    {
        $songId = (new RadioGamesModel())->retrieveActiveSongId();
        return $this->dbc->query("SELECT name FROM songs WHERE id = $songId")[0]->name;
    }

    public function retrieveAuthorName(): string
    {
        $songId = (new RadioGamesModel())->retrieveActiveSongId();
        return $this->dbc->query("SELECT author_name FROM songs WHERE id = $songId")[0]->author_name;
    }
}

<?php

namespace App\Models;

class SongModel extends Model
{
    public function retrieveRadioSongName(): string
    {
        $songId = (new GameModel())->retrieveActiveSongId();
        return $this->dbc->query("SELECT name FROM songs WHERE id = $songId")[0]->name;
    }

    public function retrieveAuthorName(): string
    {
        $songId = (new GameModel())->retrieveActiveSongId();
        return $this->dbc->query("SELECT author_name FROM songs WHERE id = $songId")[0]->author_name;
    }
}

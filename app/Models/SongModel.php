<?php

namespace App\Models;

class SongModel extends Model
{
    public function retrieveRadioSongName(): string
    {
        $songId = (new GameModel())->retrieveActiveSongId();
        return $this->dbc->fetchPrepared("SELECT name FROM songs WHERE id = ?", [$songId])[0]->name;
    }

    public function retrieveAuthorName(): string
    {
        $songId = (new GameModel())->retrieveActiveSongId();
        return $this->dbc->fetchPrepared("SELECT author_name FROM songs WHERE id = ?", [$songId])[0]->author_name;
    }

    public function retrieveVideoId(): string
    {
        $songId = (new GameModel())->retrieveActiveSongId();
        return $this->dbc->fetchPrepared("SELECT video_id FROM songs WHERE id = ?", [$songId])[0]->video_id;
    }
}

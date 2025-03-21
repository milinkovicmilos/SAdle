<?php

namespace App\Models;

class SongModel extends Model
{
    public function retrieveRadioSongName(int $songId): string
    {
        return $this->dbc->fetchPrepared("SELECT name FROM songs WHERE id = ?", [$songId])[0]->name;
    }

    public function retrieveAuthorName(int $songId): string
    {
        return $this->dbc->fetchPrepared("SELECT author_name FROM songs WHERE id = ?", [$songId])[0]->author_name;
    }

    public function retrieveVideoId(int $songId): string
    {
        return $this->dbc->fetchPrepared("SELECT video_id FROM songs WHERE id = ?", [$songId])[0]->video_id;
    }

    public function retrieveSongsRadio(int $songId): int
    {
        return $this->dbc->fetchPrepared("SELECT radio_id FROM songs WHERE id = ?", [$songId])[0]->radio_id;
    }
}

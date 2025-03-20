<?php

namespace App\Controllers;

use App\Models\SongModel;
use App\Models\GameModel;

class SongController extends Controller
{
    private SongModel $model;
    private GameModel $gameModel;

    public function __construct()
    {
        $this->model = new SongModel();
        $this->gameModel = new GameModel();
    }

    public function getSongName(): void
    {
        try {
            $songId = $this->gameModel->retrieveActiveSongId();
            $data = $this->model->retrieveRadioSongName($songId);
            $data = [
                "elementId" => "song-name",
                "name" => $data
            ];
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error while retrieving data."
            ];
            http_response_code(500);
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }
}

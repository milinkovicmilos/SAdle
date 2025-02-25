<?php

namespace App\Controllers;

use App\Models\GameModel;
use App\Models\SongModel;

class GameController extends Controller
{
    private GameModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GameModel();
    }

    public function getCurrentDate()
    {
        try {
            $data = $this->model->retrieveCurrentDate();
            $data = [
                "date" => $data
            ];
        } catch (\Exception | \Error $e) {
            $data = [
                "message" => "Error in fetching date."
            ];
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }

    public function radioGuess()
    {
        try {
            $correctRadioId = $this->model->retrieveCorrectRadioId();
            $correct = $correctRadioId == $this->json->id;
            $clues = [];
            $songModel = new SongModel();
            $songId = $this->model->retrieveActiveSongId();

            if ($correct) {
                $clues[] = [
                    "elementId" => "author-name",
                    "value" => $songModel->retrieveAuthorName($songId),
                ];
                $clues[] = [
                    "elementId" => "music-video",
                    "value" => $songModel->retrieveVideoId($songId),
                ];
            } else {
                $obj = new \stdClass();
                switch ($this->json->guess_number) {
                    case 2:
                        $clues[] = [
                            "elementId" => "author-name",
                            "value" => $songModel->retrieveAuthorName($songId),
                        ];
                        break;

                    case 3:
                        $clues[] = [
                            "elementId" => "music-video",
                            "value" => $songModel->retrieveVideoId($songId),
                        ];
                        break;
                }
            }
            $data = [
                "correct" => $correct,
                "clues" => $clues,
            ];
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error in processing radio guess."
            ];
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }
}

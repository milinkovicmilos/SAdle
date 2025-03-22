<?php

namespace App\Controllers;

use App\Models\GameModel;
use App\Models\SongModel;
use Exception;

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
            $currentDate = $this->model->retrieveCurrentDate();
            $timeToNextDay = $this->model->retrieveSecondsToNextDay();
            $data = [
                "date" => $currentDate,
                "timeToNextDay" => $timeToNextDay,
            ];
        } catch (\Exception | \Error $e) {
            $data = [
                "message" => "Error in fetching date."
            ];
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }

    public function getSongName(): void
    {
        try {
            $songModel = new SongModel();
            $songId = $this->model->retrieveActiveSongId();
            $data = $songModel->retrieveRadioSongName($songId);
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

    public function radioGuess()
    {
        try {
            // Validation
            if (!property_exists($this->json, "id") || empty($this->json->id) || !is_numeric($this->json->id)) {
                throw new Exception("Submitted Radio ID is invalid.");
            }

            if (!property_exists($this->json, "guessNumber") || empty($this->json->guessNumber) || !is_numeric($this->json->guessNumber)) {
                throw new Exception("Submitted guessNumber is invalid");
            }

            $submittedRadioId = $this->json->id;
            $guessNumber = $this->json->guessNumber;

            $clues = [];
            $songModel = new SongModel();

            $songId = $this->model->retrieveActiveSongId();
            $correctRadioId = $songModel->retrieveSongsRadio($songId);
            $correct = $correctRadioId == $submittedRadioId;

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
                switch ($guessNumber) {
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

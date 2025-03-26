<?php

namespace App\Controllers;

use App\Models\GameModel;
use App\Models\MissionGameCluesModel;
use App\Models\MissionModel;
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

    public function getCurrentDate(): void
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

    public function radioGuess(): void
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
            http_response_code(500);
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }

    private function getMissionClue(int $clueNumber, string $attributeToGuess): array
    {
        $missionsModel = new MissionModel();
        $missionGameCluesModel = new MissionGameCluesModel();

        $currentGameId = $this->model->retrieveCurrentGameId();

        $cluesOrder = $missionGameCluesModel->retrieveMissionClueOrder($currentGameId, $attributeToGuess);
        $cluesOrder = str_replace(',', '","', $cluesOrder);
        $cluesOrder = json_decode('["' . $cluesOrder . '"]');

        if ($clueNumber > count($cluesOrder)) {
            return [];
        }

        $clueName = $cluesOrder[$clueNumber - 1];

        $missionId = $this->model->retrieveCurrentMissionId($attributeToGuess);
        $value = $missionsModel->retrieveMissionInfo($clueName, $missionId);

        return [
            "attributeToGuess" => $attributeToGuess,
            "elementClass" => $clueName,
            "value" => $value,
        ];
    }

    public function getFirstMissionClues(): void
    {
        try {
            $data = [];
            $data[] = $this->getMissionClue(1, "title");
            $data[] = $this->getMissionClue(1, "origin");
            $data[] = $this->getMissionClue(1, "giver");
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error in getting initial mission clues."
            ];
            http_response_code(500);
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }

    private function handleMissionGuess(string $attributeToGuess, int $guessNumber, bool $correct, int $missionId): array
    {
        $missionModel = new MissionModel();
        $clues = [];

        if ($correct) {
            $missionAttributes = $missionModel->retrieveAllMissionAttributes($missionId);
            foreach ($missionAttributes as $key => $value) {
                $clues[] = [
                    "attributeToGuess" => $attributeToGuess,
                    "elementClass" => $key,
                    "value" => $value,
                ];
            }

            return [
                "correct" => $correct,
                "clues" => $clues,
            ];
        }

        $clues[] = $this->getMissionClue($guessNumber + 1, $attributeToGuess);
        if ($clues === [[]]) {
            $clues = [];
        }

        return [
            "correct" => $correct,
            "clues" => $clues,
        ];
    }

    public function missionTitleGuess(): void
    {
        try {
            // Validation
            if (!property_exists($this->json, "id") || empty($this->json->id) || !is_numeric($this->json->id)) {
                throw new Exception("Submitted Radio ID is invalid.");
            }

            if (!property_exists($this->json, "guessNumber") || empty($this->json->guessNumber) || !is_numeric($this->json->guessNumber)) {
                throw new Exception("Submitted guessNumber is invalid");
            }

            $submittedMissionId = $this->json->id;
            $guessNumber = $this->json->guessNumber;

            $currentTitleMissionId = $this->model->retrieveCurrentTitleMissionId();
            $correct = $currentTitleMissionId == $submittedMissionId;
            $data = $this->handleMissionGuess("title", $guessNumber, $correct, $currentTitleMissionId);
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error in processing guess."
            ];
            http_response_code(500);
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }

    public function missionOriginGuess(): void
    {
        try {
            // Validation
            if (!property_exists($this->json, "id") || empty($this->json->id) || !is_numeric($this->json->id)) {
                throw new Exception("Submitted Radio ID is invalid.");
            }

            if (!property_exists($this->json, "guessNumber") || empty($this->json->guessNumber) || !is_numeric($this->json->guessNumber)) {
                throw new Exception("Submitted guessNumber is invalid");
            }

            $submittedOriginId = $this->json->id;
            $guessNumber = $this->json->guessNumber;

            $missionModel = new MissionModel();

            $currentOriginMissionId = $this->model->retrieveCurrentOriginMissionId();
            $currentOriginId = $missionModel->retrieveMissionOriginId($currentOriginMissionId);

            $correct = $currentOriginId == $submittedOriginId;
            $data = $this->handleMissionGuess("origin", $guessNumber, $correct, $currentOriginMissionId);
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error in processing guess."
            ];
            http_response_code(500);
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }

    public function missionGiverGuess(): void
    {
        try {
            // Validation
            if (!property_exists($this->json, "id") || empty($this->json->id) || !is_numeric($this->json->id)) {
                throw new Exception("Submitted Radio ID is invalid.");
            }

            if (!property_exists($this->json, "guessNumber") || empty($this->json->guessNumber) || !is_numeric($this->json->guessNumber)) {
                throw new Exception("Submitted guessNumber is invalid");
            }

            $submittedGiverId = $this->json->id;
            $guessNumber = $this->json->guessNumber;

            $missionModel = new MissionModel();

            $currentGiverMissionId = $this->model->retrieveCurrentGiverMissionId();
            $currentGiverId = $missionModel->retrieveMissionGiverId($currentGiverMissionId);

            $correct = $currentGiverId == $submittedGiverId;
            $data = $this->handleMissionGuess("giver", $guessNumber, $correct, $currentGiverMissionId);
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error in processing guess."
            ];
            http_response_code(500);
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }
}

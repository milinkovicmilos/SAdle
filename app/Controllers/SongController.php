<?php

namespace App\Controllers;

use App\Models\SongModel;

class SongController extends Controller
{
    private SongModel $model;

    public function __construct()
    {
        $this->model = new SongModel();
    }

    public function getSongName(): void
    {
        try {
            $data = $this->model->retrieveRadioSongName();
            $data = [
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

    public function getAuthorName(): void
    {
        try {
            $data = $this->model->retrieveAuthorName();
            $data = [
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

    public function getVideoId(): void
    {
        try {
            $data = $this->model->retrieveVideoId();
            $data = [
                "video_id" => $data
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

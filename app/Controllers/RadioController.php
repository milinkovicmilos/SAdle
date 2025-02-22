<?php

namespace App\Controllers;

use App\Models\RadioModel;

class RadioController extends Controller
{
    private RadioModel $model;

    public function __construct()
    {
        $this->model = new RadioModel();
    }

    public function getRadioStations(): void
    {
        try {
            $data = $this->model->retrieveRadioStations();
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

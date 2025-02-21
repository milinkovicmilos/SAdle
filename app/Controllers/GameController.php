<?php

namespace App\Controllers;

use App\Models\GameModel;

class GameController extends Controller
{
    private GameModel $model;

    public function __construct()
    {
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
}

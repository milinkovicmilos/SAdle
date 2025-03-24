<?php

namespace App\Controllers;

use App\Models\MissionGiverModel;

class MissionGiverController extends Controller
{

    private MissionGiverModel $model;

    public function __construct()
    {
        $this->model = new MissionGiverModel();
    }

    public function getMissionGivers(): void
    {
        try {
            $data = $this->model->retrieveMissionGivers();
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error while fetching mission givers..."
            ];
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }
}

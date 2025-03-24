<?php

namespace App\Controllers;

use App\Models\MissionModel;

class MissionController extends Controller
{

    private MissionModel $model;

    public function __construct()
    {
        $this->model = new MissionModel();
    }

    public function getMissionTitles(): void
    {
        try {
            $data = $this->model->retrieveMissionTitles();
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error while fetching mission titles..."
            ];
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }
}

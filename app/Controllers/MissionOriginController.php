<?php

namespace App\Controllers;

use App\Models\MissionOriginModel;

class MissionOriginController extends Controller
{

    private MissionOriginModel $model;

    public function __construct()
    {
        $this->model = new MissionOriginModel();
    }

    public function getMissionOrigins(): void
    {
        try {
            $data = $this->model->retrieveMissionOrigins();
        } catch (\PDOException | \Exception | \Error $e) {
            $data = [
                "message" => "Error while fetching mission origins..."
            ];
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }
}

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
}

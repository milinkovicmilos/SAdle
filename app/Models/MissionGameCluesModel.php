<?php

namespace App\Models;

class MissionGameCluesModel extends Model
{
    public function retrieveMissionClueOrder(int $gameId, int $missionNumber): string
    {
        return $this->dbc->fetchPrepared(
            "SELECT clue_order FROM mission_game_clues WHERE game_id = ? AND mission_number = ?",
            [
                $gameId,
                $missionNumber
            ]
        )[0]->clue_order;
    }
}

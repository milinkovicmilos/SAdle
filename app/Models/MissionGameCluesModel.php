<?php

namespace App\Models;

class MissionGameCluesModel extends Model
{
    public function retrieveMissionClueOrder(int $gameId, string $attributeToGuess): string
    {
        return $this->dbc->fetchPrepared(
            "SELECT clue_order FROM mission_game_clues WHERE game_id = ? AND attribute_to_guess = ?",
            [
                $gameId,
                $attributeToGuess
            ]
        )[0]->clue_order;
    }
}

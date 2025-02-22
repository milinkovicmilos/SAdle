<?php

namespace App\Models;

class RadioModel extends Model
{
    public function retrieveRadioStations(): array
    {
        return $this->dbc->selectAll("radio_stations");
    }
}

<?php

namespace App\Config;

class EnvReader
{
    const ENVSPATH = '/Envs/';

    public static function getEnvData(): array
    {
        $data = array();
        // TODO : Detect current env and read the appropriate one
        $file = file(__DIR__ . self::ENVSPATH . '.env');
        foreach ($file as $row) {
            list($key, $value) = explode('=', $row);
            $data[$key] = trim($value);
        }
        return $data;
    }
}

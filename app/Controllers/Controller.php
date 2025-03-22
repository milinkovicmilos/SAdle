<?php

namespace App\Controllers;

abstract class Controller
{
    protected array $queryParameters = [];
    protected array | object $json;

    public function __construct()
    {
        // Check if query string exists
        if (str_contains($_SERVER["REQUEST_URI"], '?')) {
            $queryString = explode('?', $_SERVER["REQUEST_URI"])[1];
            parse_str($queryString, $queryString);
        }

        $data = json_decode(file_get_contents('php://input'));
        if ($data === null) {
            $this->json = new \stdClass();
        } else {
            $this->json = $data;
        }
    }
}

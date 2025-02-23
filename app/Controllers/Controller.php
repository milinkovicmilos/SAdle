<?php

namespace App\Controllers;

abstract class Controller
{
    protected array $queryParameters = [];
    protected array | object $json;

    public function __construct()
    {
        $queryString = explode('?', $_SERVER["REQUEST_URI"])[1];
        if (isset($queryString)) {
            foreach (explode('&', $queryString) as $value) {
                if (!empty($value)) {
                    list($param, $val) = explode('=', $value);
                    $this->queryParameters[$param] = $val;
                }
            }
        }

        $data = json_decode(file_get_contents('php://input'));
        if ($data === null) {
            $this->json = new \stdClass();
        } else {
            $this->json = $data;
        }
    }
}

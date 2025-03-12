<?php

use App\Router;
use App\Enums\RequestMethod;

require_once '../../vendor/autoload.php';

const VIEWSPATH = '../Views/';

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];
$route = explode('?', $request)[0];

$router = new Router();

if ($route == '/')
    $route = '/radio';

$res = $router->matchRoute(RequestMethod::from($method), $route);
if ($res) {
    try {
        $router->invokeControllerMethod(...$res);
    } catch (Exception | Error $e) {
        http_response_code(500);
        exit("Error...");
    }
} else {
    $pageName = substr($route, 1);
    $page = VIEWSPATH . $pageName . '.php';

    if ($method == "GET") {
        include_once VIEWSPATH . 'Fixed/head.php';
        include_once VIEWSPATH . 'Fixed/header.php';

        if (file_exists($page))
            include_once $page;
        else
            include_once VIEWSPATH . '404.php';

        include_once VIEWSPATH . 'Fixed/footer.php';
    } else {
        http_response_code(400);
        exit();
    }
}

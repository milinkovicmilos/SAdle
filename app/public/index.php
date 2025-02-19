<?php

use App\Router;
use App\Enums\RequestMethod;

require_once '../../vendor/autoload.php';

const VIEWSPATH = '../Views/';

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];
$route = strtok($request, '?');
$queryString = strtok('');

$queryParams = [];
if (!empty($queryString)) {
    foreach (explode('&', $queryString) as $value) {
        list($param, $val) = explode('=', $value);
        $queryParams[$param] = $val;
    }
}

$router = new Router();

$route = strtok($route, '/');
if ($route == '')
    $route = 'song';

$res = $router->MatchRoute(RequestMethod::from($method), $route);
if ($res) {
    try {
        $router->InvokeControllerMethod(...$res);
    } catch (Exception $ex) {
        http_response_code(500);
        exit("Error...");
    } catch (Error $err) {
        http_response_code(500);
        exit("Error...");
    }
} else {
    $page = VIEWSPATH . $route . '.php';

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

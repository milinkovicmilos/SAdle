<?php

require_once '../../vendor/autoload.php';

const VIEWSPATH = '../Views';

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

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        include_once VIEWSPATH . '/Fixed/head.php';
        include_once VIEWSPATH . '/Fixed/header.php';

        if ($route == '/')
            $route = '/index';

        $page = VIEWSPATH . $route . '.php';
        if (file_exists($page))
            include_once $page;
        else
            include_once VIEWSPATH . '/404.php';

        include_once VIEWSPATH . '/Fixed/footer.php';
        break;

    case 'POST':
        break;
}

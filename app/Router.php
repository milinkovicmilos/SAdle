<?php

namespace App;

use App\Enums\RequestMethod;
use Exception;

class Router
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [];
        $this->addRoute(RequestMethod::GET, "/GetCurrentDate", \App\Controllers\GameController::class, "getCurrentDate");
        $this->addRoute(RequestMethod::POST, "/RadioGuess", \App\Controllers\GameController::class, "radioGuess");
        $this->addRoute(RequestMethod::GET, "/GetRadioStations", \App\Controllers\RadioController::class, "getRadioStations");
        $this->addRoute(RequestMethod::GET, "/GetSongName", \App\Controllers\GameController::class, "getSongName");
        $this->addRoute(RequestMethod::GET, "/GetFirstMissionClues", \App\Controllers\GameController::class, "getFirstMissionClues");
        $this->addRoute(RequestMethod::GET, "/GetMissionTitles", \App\Controllers\MissionController::class, "getMissionTitles");
        $this->addRoute(RequestMethod::GET, "/GetMissionGivers", \App\Controllers\MissionGiverController::class, "getMissionGivers");
    }

    public function addRoute(RequestMethod $method, string $route, string $controller, string $action): void
    {
        $this->routes[] = (object)[
            "method" => $method,
            "route" => $route,
            "controller" => $controller,
            "action" => $action,
        ];
    }

    public function matchRoute(RequestMethod $method, string $route): array | bool
    {
        foreach ($this->routes as $routeObj) {
            if ($routeObj->method == $method && $routeObj->route == $route) {
                return [$routeObj->controller, $routeObj->action];
            }
        }
        return false;
    }

    public function invoke(RequestMethod $method, string $route): void
    {
        list($controller, $actionMethod) = $this->MatchRoute($method, $route);
        $this->Invoke($controller, $actionMethod);
    }

    public function invokeControllerMethod(string $controller, string $actionMethod)
    {
        if (empty($controller) || empty($actionMethod))
            throw new Exception("Invalid Route.");

        if (!class_exists($controller))
            throw new Exception("Controller class doesn't exist.");

        if (!method_exists($controller, $actionMethod))
            throw new Exception("Action method doesn't exist.");

        (new $controller())->$actionMethod();
    }
}

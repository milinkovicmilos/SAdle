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
    }

    public function AddRoute(RequestMethod $method, string $route, string $controller, string $action): void
    {
        $this->routes[] = (object)[
            "method" => $method,
            "route" => $route,
            "controller" => $controller,
            "action" => $action,
        ];
    }

    public function MatchRoute(RequestMethod $method, string $route): array | bool
    {
        foreach ($this->routes as $routeObj) {
            if ($routeObj->method == $method && $routeObj->route == $route) {
                return [$routeObj->controller, $routeObj->action];
            }
        }
        return false;
    }

    public function Invoke(RequestMethod $method, string $route): void
    {
        list($controller, $actionMethod) = $this->MatchRoute($method, $route);
        $this->Invoke($controller, $actionMethod);
    }

    public function InvokeControllerMethod(string $controller, string $actionMethod)
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

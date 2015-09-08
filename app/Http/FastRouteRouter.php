<?php
namespace MicroFramework\Http;

use FastRoute;
use FastRoute\RouteCollector;

class FastRouteRouter implements RouterInterface
{
    protected $routes = [];

    protected $dispatchedRoute;

    public function addRoute ($action, $route, $controller, $method)
    {
        $this->routes[] = [
            'action'  => $action,
            'route'   => $route,
            'handler' => [$controller, $method]
        ];
    }

    public function get ($route, $controller, $method)
    {
        $this->addRoute('GET', $route, $controller, $method);
    }

    public function dispatch ()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $route) {
                 $r->addRoute($route['action'], $route['route'], $route['handler']);
             }
        });

        $this->dispatchedRoute = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    public function getController ()
    {
        return $this->dispatchedRoute[1];
    }

    public function getParams ()
    {
        return $this->dispatchedRoute[2];
    }

    public function found ()
    {
        return $this->dispatchedRoute[0] === FastRoute\Dispatcher::FOUND;
    }

    public function notFound ()
    {
        return $this->dispatchedRoute[0] === FastRoute\Dispatcher::NOT_FOUND;
    }

    public function methodNotAllowed ()
    {
        return $this->dispatchedRoute[0] === FastRoute\Dispatcher::METHOD_NOT_ALLOWED;
    }

}

<?php
namespace MicroFramework\Http;

use Aura\Router\RouterFactory;
use Aura\Router\Route;

class AuraRouter implements RouterInterface
{
    protected $router;

    protected $dispatchedRoute;

    public function __construct (RouterFactory $routerFactory)
    {
        $routerFactory = new RouterFactory;
        $this->router  = $routerFactory->newInstance();
    }

    public function addRoute ($action, $route, $controller, $method)
    {
        $action = 'add' . ucfirst(strtolower($action));

        $this->router->{$action}($controller . '::' . $method, $route);
    }

    public function get ($route, $controller, $method)
    {
        $this->addRoute('GET', $route, $controller, $method);
    }

    public function dispatch ()
    {
        $routerFactory = new RouterFactory;
        $router        = $routerFactory->newInstance();

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $this->dispatchedRoute = $this->router->match($path, $_SERVER);
    }

    public function getController ()
    {
        return explode("::", $this->dispatchedRoute->name);
    }

    public function getParams ()
    {
        return $this->dispatchedRoute->params;
    }

    public function found ()
    {
        return $this->router->getFailedRoute() === null;
    }

    public function notFound ()
    {
        $failedRoute = $this->router->getFailedRoute();

        if ($failedRoute === null) {
            return false;
        }

        return $failedRoute->failed === Route::FAILED_ROUTABLE || $failedRoute->failed === Route::FAILED_REGEX;
    }

    public function methodNotAllowed ()
    {
        $failedRoute = $this->router->getFailedRoute();

        if ($failedRoute === null) {
            return false;
        }

        return $failedRoute->failed === Route::FAILED_METHOD;
    }
}

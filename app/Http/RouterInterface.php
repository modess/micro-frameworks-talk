<?php
namespace MicroFramework\Http;

interface RouterInterface
{
    public function dispatch();

    public function get($route, $controller, $method);

    public function addRoute ($action, $route, $controller, $method);

    public function found();

    public function notFound();

    public function methodNotAllowed();

    public function getController();

    public function getParams();
}

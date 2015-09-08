<?php
require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use function DI\object;
use MicroFramework\Http\AuraRouter;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Router::class => object(AuraRouter::class)
]);
$container = $containerBuilder->build();

$router = $container->get('Router');

$router->get('/', 'MicroFramework\Controllers\HomeController', 'index');

$router->dispatch();

if ($router->notFound()) {
    echo '404';
} elseif ($router->methodNotAllowed()) {
    echo '403';
} elseif ($router->found()) {
    $container->call($router->getController(), $router->getParams());
}

echo '<br><br>from: ' . get_class($router);

<?php declare(strict_types=1);

require_once "vendor/autoload.php";

use App\Controllers\ViewsController;
use App\Models\GiphyApiClient;
use FastRoute\RouteCollector;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loader = new FilesystemLoader('app/Views');
$twig = new Environment($loader);


$giphyApiClient = new GiphyApiClient();


$controller = new ViewsController($giphyApiClient,$twig);


$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->get('/', [ViewsController::class, 'index']);
    $r->get('/trending', [ViewsController::class, 'trending']);
    $r->get('/search', [ViewsController::class, 'search']);
});

$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($requestMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$className, $methodName] = $handler;
        $controller = new $className($giphyApiClient,$twig);
        $response = $controller->{$methodName}();
        break;
}





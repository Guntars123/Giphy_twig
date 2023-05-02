<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$routes = require_once "../routes.php";
$response = Router::response($routes);

$renderer = new Renderer('../app/Views');

echo $renderer->render($response);











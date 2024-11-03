<?php declare(strict_types = 1);

namespace App;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = getenv('ENVIRONMENT');

// Register the error handler
$whoops = new \Whoops\Run;
switch ($environment) {
    case 'development':
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        break;
    case 'production':
    default:
        $whoops->pushHandler(function($e) {
            echo 'There was an error. Please contact us at support@example.com';
            error_log($e->getMessage() . PHP_EOL, 3, __DIR__ . '/../logs/err.log');
            // This would normally send an email. Adding only as an example of logging messages.
            error_log($e->getMessage(), 1, "operator@example.com");
        });
}

$whoops->register();

$injector = include('Dependencies.php');
$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

foreach ($response->getHeaders() as $header) {
    header($header, false);
}

$routeDefinitionCallback = function(\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        echo $response->getContent();
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        echo $response->getContent();
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        // If user not allowed, redirect to homepage
        $accessAllowed = \App\Booking\User::hasAccess($routeInfo[1][2], $vars);
        if (!$accessAllowed) {
            $userType = \App\Booking\User::getLoggedUserType();
            if ($userType === 'guest') {
                header('Location: /login');
            } else {
                header('Location: /');
            }
            return;
        }

        $class = $injector->make($className);
        $class->$method($vars);
        echo $response->getContent();
        break;
}
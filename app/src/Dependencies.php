<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':getParameters' => $_GET,
    ':postParameters' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->alias('App\Template\FrontendRenderer', 'App\Template\FrontendTwigRenderer');
$injector->alias('App\Template\Renderer', 'App\Template\TwigRenderer');
$injector->delegate('\Twig\Environment', function () use ($injector) {
    $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
    $twig = new \Twig\Environment($loader);
    $twig->addGlobal('session', $_SESSION);
    $func = new \Twig\TwigFunction('getUserType', function () {
        if (!empty($_SESSION['logged_id']) && isset($_SESSION['isVendor'])) {
            return ($_SESSION['isVendor'] === 'true') ? 'vendor' : 'client';
        }

        return 'guest';
    });
    $twig->addFunction($func);
    return $twig;
});

$injector->define('App\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);
$injector->alias('App\Page\PageReader', 'App\Page\FilePageReader');
$injector->share('App\Page\FilePageReader');

$injector->alias('App\Menu\MenuReader', 'App\Menu\ArrayMenuReader');
$injector->share('App\Menu\ArrayMenuReader');

return $injector;
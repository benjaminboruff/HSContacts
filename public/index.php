<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\TwigMiddleware;
use DI\Bridge\Slim\Bridge;
use App\Action\HomeController;
use App\Action\UserController;
use App\Action\ContactController;
use Psr\Container\ContainerInterface;

$container = require_once __DIR__ . '/../bootstrap.php';

// set flash as a global var for twig files
$container->get('view')->getEnvironment()->addGlobal('flash', $container->get('flash'));

$settings = $container->get('settings');

$app = Bridge::create($container);

//
// middleware
//
$app->add(TwigMiddleware::createFromContainer($app));

// session start middleware
$app->add(
    function ($request, $next) {
        // Start PHP session
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Change flash message storage
        $this->get('flash')->__construct($_SESSION);

        return $next->handle($request);
    }
);

$app->addErrorMiddleware(
    $settings['slim']['displayErrorDetails'],
    $settings['slim']['logErrors'],
    $settings['slim']['logErrorDetails']
);

//
// static routes
//
$app->get('/', [HomeController::class, 'index']);
$app->get('/users', [UserController::class, 'index']);
$app->get('/contacts', [ContactController::class, 'index']);
$app->get('/contacts/new', [ContactController::class, 'create']);
$app->post('/contacts/new', [ContactController::class, 'store']);

$app->get('/about', function (Request $request, Response $response, ContainerInterface $c) {

    if ($request->hasHeader('HX-Request')) {
        return $c->get('view')->render($response, 'partial/about.twig');
    } else {
        return $c->get('view')->render($response, 'full/about.twig');
    }
});

//
// dynamic routes
//
$app->get('/contacts/{id}', [ContactController::class, 'show']);
$app->post('/contacts/{id}/delete', [ContactController::class, 'delete']);
$app->get('/contacts/{id}/edit', [ContactController::class, 'edit']);
$app->post('/contacts/{id}/edit', [ContactController::class, 'update']);

$app->run();

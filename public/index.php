<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use DI\Bridge\Slim\Bridge;
use App\Action\HomeController;
use App\Action\UserController;
use App\Action\ContactController;

$container = require_once __DIR__ . '/../bootstrap.php';

$app = Bridge::create($container);

$twig = Twig::create(__DIR__ . '/../src/View', ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twig));
$app->addErrorMiddleware(true, false, false);

// routes
$app->get('/', [HomeController::class, 'index'])->setName('index');
$app->get('/users', [UserController::class, 'index'])->setName('all_users');
$app->get('/contacts', [ContactController::class, 'index'])->setName('all_contacts');

$app->get('/about', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'about.twig');
});

$app->run();

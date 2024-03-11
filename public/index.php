<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use DI\Bridge\Slim\Bridge;
use App\Action\HomeController;
use App\Action\UserController;
use App\Action\ContactController;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;

$container = require_once __DIR__ . '/../bootstrap.php';

$app = Bridge::create($container);

$twig = Twig::create(__DIR__ . '/../src/View', ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twig));

// Add session start middleware
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

$app->addErrorMiddleware(true, false, false);

// static routes
$app->get('/', [HomeController::class, 'index']);
$app->get('/users', [UserController::class, 'index']);
$app->get('/contacts', [ContactController::class, 'index']);
$app->get('/contacts/new', [ContactController::class, 'create']);
$app->post('/contacts/new', [ContactController::class, 'store']);

$app->get('/about', function (Request $request, Response $response, ContainerInterface $c) {
    $view = Twig::fromRequest($request);
    $flash = $c->get('flash');
    $messages = $flash->getMessages();
    print_r($messages);

    // Get the first message from a specific key
    $test = $flash->getFirstMessage('Test');
    print_r($test);

    if ($request->hasHeader('HX-Request')) {
        return $view->render($response, 'partial/about.twig');
    } else {
        return $view->render($response, 'full/about.twig');
    }
});

// dynamic routes
$app->get('/contacts/{id}', [ContactController::class, 'show']);

$app->run();

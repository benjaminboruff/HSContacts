<?php
// HomeController.php

namespace App\Action;

use \Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController
{

    public function __construct(protected ContainerInterface $c)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        // Set flash message for next request
        $this->c->get('flash')->addMessage('Test', 'This is a message');
        $view = Twig::fromRequest($request);

        return $view->render($response, 'full/index.twig');
    }
}

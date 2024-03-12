<?php
// HomeController.php

namespace App\Action;

use \Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{

    public function __construct(private ContainerInterface $c)
    {
    }

    public function index(Request $request, Response $response): Response
    {

        // Set flash message for next request
        // $this->c->get('flash')->addMessage('errors', ['foo', 'bar']);
        // $this->c->get('flash')->addMessage('test2', 'This is another message');

        // dump(session_status());
        // die();

        return $this->c->get('view')->render($response, 'full/index.twig');
    }
}

<?php
// UserController.php

namespace App\Action;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use \Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class UserController
{
    private UserRepository $userRepository;
    private Twig $view;

    public function __construct(private ContainerInterface $c)
    {
        $this->userRepository = $c->get(EntityManager::class)->getRepository('App\Domain\User');
        $this->view = $c->get('view');
    }

    public function index(Request $request, Response $response): Response
    {
        $users = $this->userRepository->getAllUsers();

        if ($request->hasHeader('HX-Request')) {
            return $this->view->render($response, 'partial/users.twig', ['users' => $users]);
        } else {
            return $this->view->render($response, 'full/users.twig', ['users' => $users]);
        }
    }
}

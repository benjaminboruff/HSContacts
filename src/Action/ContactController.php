<?php
// ContactController.php

namespace App\Action;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
use \Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ContactController
{
    private ContactRepository $contactRepository;

    public function __construct(ContainerInterface $c)
    {
        $this->contactRepository = $c->get(EntityManager::class)->getRepository('App\Domain\Contact');
    }

    public function index(Request $request, Response $response): Response
    {
        $users = $this->contactRepository->getAllContacts();
        $view = Twig::fromRequest($request);

        return $view->render($response, 'users.twig', ['users' => $users]);
    }
}

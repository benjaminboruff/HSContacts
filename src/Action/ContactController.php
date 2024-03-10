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

    public function index(Request $request, Response $response, array $args = null): Response
    {
        $view = Twig::fromRequest($request);
        $search = $request->getQueryParams()['q'] ?? null;
        $contacts = null;


        if ($search) {
            $contacts = $this->contactRepository->getSearchContacts($search);
        } else {
            $contacts = $this->contactRepository->getAllContacts();
        }

        if ($request->hasHeader('HX-Request')) {
            return $view->render($response, 'partial/contacts.twig', ['contacts' => $contacts, 'search' => $search]);
        } else {
            return $view->render($response, 'full/contacts.twig', ['contacts' => $contacts, 'search' => $search]);
        }
    }
}

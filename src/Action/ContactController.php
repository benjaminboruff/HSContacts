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
    // private array|null $contacts = null;

    public function __construct(ContainerInterface $c)
    {
        $this->contactRepository = $c->get(EntityManager::class)->getRepository('App\Domain\Contact');
    }

    public function index(Request $request, Response $response, array $args = null): Response
    {
        $search = $request->getQueryParams()['q'] ?? null;
        $contacts = null;

        if ($search) {
            $contacts = $this->contactRepository->searchContacts($search);
        } else {
            $contacts = $this->contactRepository->getAllContacts();
        }

        $view = Twig::fromRequest($request);

        return $view->render($response, 'contacts.twig', ['contacts' => $contacts, 'search' => $search]);
    }
}

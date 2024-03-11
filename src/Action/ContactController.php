<?php
// ContactController.php

namespace App\Action;

use App\Domain\Contact;
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

    public function show(Request $request, Response $response): Response
    {
        $args = (array)$request->getAttributes();
        $contact = $this->contactRepository->getContactById($args['id']);
        $view = Twig::fromRequest($request);

        if ($request->hasHeader('HX-Request')) {
            return $view->render($response, 'partial/show_contact.twig', ['contact' => $contact]);
        } else {
            return $view->render($response, 'full/show_contact.twig', ['contact' => $contact]);
        }
    }

    public function create(Request $request, Response $response): Response
    {
        $view = Twig::fromRequest($request);

        if ($request->hasHeader('HX-Request')) {
            return $view->render($response, 'partial/new_contact.twig');
        } else {
            return $view->render($response, 'full/new_contact.twig');
        }
    }

    public function store(Request $request, Response $response): Response
    {
        $params = (array)$request->getParsedBody();

        $contact = new Contact;
        $contact->setFirstName($params['first_name']);
        $contact->setLastName($params['last_name']);
        $contact->setEmail($params['email']);
        $contact->setPhone($params['phone']);
        $contact->setRegisteredAt();

        $this->contactRepository->setNewContact($contact);
        $savedContact = $this->contactRepository->getContactByEmail($params['email']);

        return $response
            ->withHeader('Location', '/contacts/' . (string)$savedContact->getId())
            ->withStatus(302);
    }
}

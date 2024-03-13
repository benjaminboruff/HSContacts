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
    private Twig $view;

    public function __construct(private ContainerInterface $c)
    {
        $this->contactRepository = $c->get(EntityManager::class)->getRepository('App\Domain\Contact');
        $this->view = $c->get('view');
    }

    public function index(Request $request, Response $response, array $args = null): Response
    {
        $search = $request->getQueryParams()['q'] ?? null;
        $contacts = null;


        if ($search) {
            $contacts = $this->contactRepository->getSearchContacts($search);
        } else {
            $contacts = $this->contactRepository->getAllContacts();
        }

        if ($request->hasHeader('HX-Request')) {
            return $this->view->render($response, 'partial/contacts.twig', ['contacts' => $contacts, 'search' => $search]);
        } else {
            return $this->view->render($response, 'full/contacts.twig', ['contacts' => $contacts, 'search' => $search]);
        }
    }

    public function show(Request $request, Response $response): Response
    {
        $args = (array)$request->getAttributes();
        $contact = $this->contactRepository->getContactById($args['id']);

        if ($request->hasHeader('HX-Request')) {
            return $this->view->render($response, 'partial/show_contact.twig', ['contact' => $contact]);
        } else {
            return $this->view->render($response, 'full/show_contact.twig', ['contact' => $contact]);
        }
    }

    public function edit(Request $request, Response $response): Response
    {
        $args = (array)$request->getAttributes();
        $contact = $this->contactRepository->getContactById($args['id']);

        if ($request->hasHeader('HX-Request')) {
            return $this->view->render($response, 'partial/edit_contact.twig', ['contact' => $contact]);
        } else {
            return $this->view->render($response, 'full/edit_contact.twig', ['contact' => $contact]);
        }
    }

    public function delete(Request $request, Response $response): Response
    {
        $args = (array)$request->getAttributes();
        $contact = $this->contactRepository->getContactById($args['id']);

        $this->contactRepository->deleteContact($contact);

        $this->c->get('flash')->addMessage('status', 'Deleted Contact!');

        return $response
            ->withHeader('Location', '/contacts')
            ->withStatus(302);
    }

    public function create(Request $request, Response $response): Response
    {

        if ($request->hasHeader('HX-Request')) {
            return $this->view->render($response, 'partial/new_contact.twig');
        } else {
            return $this->view->render($response, 'full/new_contact.twig');
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

        $this->c->get('flash')->addMessage('status', 'Created New Contact!');

        return $response
            ->withHeader('Location', '/contacts')
            ->withStatus(302);
    }
}

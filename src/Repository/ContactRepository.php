<?php
// ContactRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Domain\Contact;

final class ContactRepository extends EntityRepository
{
    public function getAllContacts($number = 30): mixed
    {
        $dql = <<<QUERY
        SELECT u FROM App\Domain\Contact u
        QUERY;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);
        return $query->getResult();
    }

    public function getSearchContacts(string $search, $number = 30): mixed
    {

        $dql = <<<QUERY
        SELECT u FROM App\Domain\Contact u
        where u.firstName = '$search' 
            OR u.lastName = '$search'
            OR u.firstName LIKE '%$search%'
            OR u.lastName LIKE '%$search%'
        QUERY;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);
        return $query->getResult();
    }

    public function getContactById(int $id): Contact
    {
        return $this->getEntityManager()->getRepository('App\Domain\Contact')->find($id);
    }

    public function getContactByEmail(string $email): Contact
    {
        return $this->getEntityManager()->getRepository('App\Domain\Contact')->findOneBy(['email' => $email]);
    }

    public function setNewContact(Contact $contact): void
    {
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();
    }

    public function deleteContact(Contact $contact): void
    {
        $this->getEntityManager()->remove($contact);
        $this->getEntityManager()->flush();
    }
}

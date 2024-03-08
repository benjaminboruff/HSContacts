<?php
// ContactRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

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

    public function searchContacts(string $search, $number = 30): mixed
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
}

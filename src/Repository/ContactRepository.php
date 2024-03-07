<?php
// ContactRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class ContactRepository extends EntityRepository
{
    public function getAllContacts($number = 30): mixed
    {
        $dql = <<<QUERY
        SELECT c FROM App\Domain\Contact c
        QUERY;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);
        return $query->getResult();
    }
}
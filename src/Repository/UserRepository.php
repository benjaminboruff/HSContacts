<?php
// UserRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class UserRepository extends EntityRepository
{
    public function getAllUsers($number = 30): mixed
    {
        $dql = <<<QUERY
        SELECT u FROM App\Domain\User u
        QUERY;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);
        return $query->getResult();
    }
}

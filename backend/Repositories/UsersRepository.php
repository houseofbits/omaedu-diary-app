<?php

namespace Backend\Repositories;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Backend\Entities\User;
use Backend\Services\EntityManagerInstance;

class UsersRepository  extends EntityRepository {
    public function __construct(EntityManagerInstance $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(User::class));
    }

    public function getUser(string $userName): ?User {
        return $this->findOneBy(['userName' => $userName]);
    }

    public function persist(User $user, bool $flush = true): void
    {
        $existing = $this->findOneBy(['userName' => $user->getUserName()]);

        if ($existing) {
            $user->setId($existing->getId());
        }

        $user->setUpdatedAt(new DateTime("now"));

        $this->getEntityManager()->merge($user);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }    
};
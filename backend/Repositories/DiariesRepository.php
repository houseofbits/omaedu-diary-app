<?php

namespace Backend\Repositories;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Backend\Entities\Diary;
use Backend\Services\EntityManagerInstance;

class DiariesRepository extends EntityRepository
{
    public function __construct(EntityManagerInstance $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Diary::class));
    }

    public function getDiary(int $id): ?Diary
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function save(Diary $diary, bool $flush = true): Diary
    {
        if ($diary->getId() == null) {
            $this->getEntityManager()->persist($diary);
        } else {
            $this->getEntityManager()->merge($diary);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $diary;
    }

    public function remove(Diary $diary, bool $flush = true)
    {
        $this->getEntityManager()->remove($diary);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

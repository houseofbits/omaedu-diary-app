<?php

namespace Backend\Repositories;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Backend\Entities\HealthRecord;
use Backend\Services\EntityManagerInstance;

class HealthRecordRepository extends EntityRepository
{
    public function __construct(EntityManagerInstance $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(HealthRecord::class));
    }

    public function getHealthRecord(int $id): ?HealthRecord
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function save(HealthRecord $object, bool $flush = true): HealthRecord
    {
        $object->setUpdatedAt(new DateTime("now"));

        if ($object->getId() == null) {
            $this->getEntityManager()->persist($object);
        } else {
            $this->getEntityManager()->merge($object);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $object;
    }

    public function remove(HealthRecord $object, bool $flush = true)
    {
        $this->getEntityManager()->remove($object);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

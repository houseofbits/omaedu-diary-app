<?php

namespace Backend\Repositories;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Backend\Entities\Chapter;
use Backend\Services\EntityManagerInstance;

class ChaptersRepository extends EntityRepository
{
    public function __construct(EntityManagerInstance $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Chapter::class));
    }

    public function getChapter(int $id): ?Chapter
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function create(Chapter $chapter, bool $flush = true): Chapter
    {
        $chapter->setUpdatedAt(new DateTime("now"));

        $this->getEntityManager()->persist($chapter);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $chapter;
    }

    public function update(Chapter $chapter, bool $flush = true): Chapter
    {
        $chapter->setUpdatedAt(new DateTime("now"));

        $this->getEntityManager()->merge($chapter);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $chapter;
    }
}
;
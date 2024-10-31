<?php

namespace Backend\Repositories;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Backend\Entities\Image;
use Backend\Services\EntityManagerInstance;

class ImagesRepository extends EntityRepository
{
    public function __construct(EntityManagerInstance $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Image::class));
    }

    public function save(Image $image, bool $flush = true): Image
    {
        $image->setUpdatedAt(new DateTime("now"));

        if ($image->getId() == null) {
            $this->getEntityManager()->persist($image);
        } else {
            $this->getEntityManager()->merge($image);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $image;
    }

    public function remove(Image $image, bool $flush = true)
    {
        $this->getEntityManager()->remove($image);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

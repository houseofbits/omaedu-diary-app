<?php

require '../vendor/autoload.php';

use Backend\Services\EntityManagerInstance;

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->safeLoad();

$em = new EntityManagerInstance();

$tool = new \Doctrine\ORM\Tools\SchemaTool($em);
$classes = array(
    $em->getClassMetadata('Backend\Entities\Chapter'),
    $em->getClassMetadata('Backend\Entities\Diary'),
    $em->getClassMetadata('Backend\Entities\HealthRecord'),
    $em->getClassMetadata('Backend\Entities\Image'),
    $em->getClassMetadata('Backend\Entities\User'),
);

$tool->dropSchema($classes);
$tool->createSchema($classes);
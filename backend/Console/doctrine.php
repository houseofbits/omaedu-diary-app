<?php
require_once './vendor/autoload.php';

/**
 * php src/Console/doctrine.php orm:schema-tool:update --force
 */
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Backend\Services\EntityManagerInstance;

$dotenv = Dotenv\Dotenv::createImmutable('./');
$dotenv->safeLoad();

$em = new EntityManagerInstance();

$helperSet = ConsoleRunner::createHelperSet($em);
ConsoleRunner::run($helperSet);
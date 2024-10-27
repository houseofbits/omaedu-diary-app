<?php

namespace Backend\Services;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;

class EntityManagerInstance extends EntityManager
{
    /**
     * @throws ORMException
     */
    public function __construct()
    {
        $config = new Configuration();
        $driverImpl = ORMSetup::createDefaultAnnotationDriver(['backend/Entities']);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setProxyDir('backend/Proxies');
        $config->setProxyNamespace('Backend\Proxies');
        $config->setAutoGenerateProxyClasses(false);

        $dbParams = array(
            'driver' => 'mysqli',
            'user' => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'dbname' => $_ENV['MYSQL_DATABASE'],
            'host' => $_ENV['MYSQL_HOST'],
        );

        $connection = static::createConnection($dbParams, $config, null);

        parent::__construct($connection, $config, $connection->getEventManager());
    }
}
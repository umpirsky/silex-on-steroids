<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Provider\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Gedmo\DoctrineExtensions;

/**
 * Doctrine Extensions service provider.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class DoctrineExtensionsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['doctrine_orm.configuration'] = $app->extend('doctrine_orm.configuration', function(Configuration $configuration) use ($app) {
            DoctrineExtensions::registerAnnotations();

            $configuration->setMetadataDriverImpl(
                $configuration->newDefaultAnnotationDriver($app['doctrine_orm.entities_path'], false)
            );

            return $configuration;
        });

        $app['doctrine_orm.connection'] = $app->extend('doctrine_orm.connection', function(Connection $connection) use ($app) {
            $eventManager = $connection->getEventManager();
            foreach($app['doctrine_orm.extensions'] as $subscriber) {
                $eventManager->addEventSubscriber($subscriber);
            }

            return $connection;
        });
    }

    public function boot(Application $app)
    {
    }
}

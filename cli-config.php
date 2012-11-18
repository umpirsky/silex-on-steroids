<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('APPLICATION_ENVIRONMENT', 'dev');

require __DIR__.'/src/bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['doctrine_orm.connection']),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['doctrine_orm.em'])
));

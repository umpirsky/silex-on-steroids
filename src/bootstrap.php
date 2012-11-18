<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!defined('APPLICATION_ROOT_PATH')) {
    define('APPLICATION_ROOT_PATH', __DIR__.'/..');
}

require_once APPLICATION_ROOT_PATH.'/vendor/autoload.php';

$app = new Sos\Application();

require APPLICATION_ROOT_PATH.'/resources/config/'.APPLICATION_ENVIRONMENT.'.php';

$app->init();

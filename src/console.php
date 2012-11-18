<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Console\Application;
use Sos\Command\AsseticDumpCommand;
use Sos\Command\FixturesLoadCommand;

$console = new Application('Silex on Steroids', '1.0');
$console->add(new AsseticDumpCommand($app));
$console->add(new FixturesLoadCommand($app));

return $console;

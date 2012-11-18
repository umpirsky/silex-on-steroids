<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Dumps assets to the filesystem.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class AsseticDumpCommand extends ApplicationAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('assetic:dump')
            ->setDescription('Dumps all assets to the filesystem.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumper = $this->app['assetic.dumper'];
        if (isset($this->app['twig'])) {
            $dumper->addTwigAssets();
        }
        $dumper->dumpAssets();

        $output->writeln('<info>Dump finished.</info>');
    }
}

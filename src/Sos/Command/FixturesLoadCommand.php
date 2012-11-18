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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Nelmio\Alice\Loader\Yaml;
use Nelmio\Alice\ORM\Doctrine;

/**
 * Loads fixtures to database.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class FixturesLoadCommand extends ApplicationAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fixtures:load')
            ->setDescription('Loads data fixtures to your database.')
            ->setDefinition(array(
                new InputArgument(
                    'src-path', InputArgument::REQUIRED, 'The path to fixtures.'
                )
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir($input->getArgument('src-path'))) {
            throw new \InvalidArgumentException(
                sprintf("Fixtures source directory '<info>%s</info>' does not exist.", $input->getArgument('src-path'))
            );
        }

        $finder = new Finder();
        $iterator = $finder
            ->files()
            ->name('*.yml')
            ->depth(0)
            ->in($input->getArgument('src-path'))
            ->sortByName()
        ;

        $loader = new Yaml();
        $persister = new Doctrine($this->app['doctrine_orm.em']);
        foreach($iterator as $file) {
            $persister->persist($loader->load($file->getPathname()));
        }

        $output->writeln('<info>Fixtures loaded.</info>');
    }
}

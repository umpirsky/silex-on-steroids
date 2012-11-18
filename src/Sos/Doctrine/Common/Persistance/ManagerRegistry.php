<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Doctrine\Common\Persistance;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Silex\Application;

/**
 * References Doctrine connections and entity/document managers.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class ManagerRegistry extends AbstractManagerRegistry
{
    /**
     * @var Application
     */
    protected $container;

    protected function getService($name)
    {
        return $this->container[$name];
    }

    protected function resetService($name)
    {
        unset($this->container[$name]);
    }

    public function getAliasNamespace($alias)
    {
        throw new \BadMethodCallException('Namespace aliases not supported.');
    }

    public function setContainer(Application $container)
    {
        $this->container = $container;
    }
}

<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Entity;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;

/**
 * Entity manager.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
abstract class EntityManager
{
    /**
     * @var DoctrineEntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @param DoctrineEntityManager $em
     * @param string                $class
     */
    public function __construct(DoctrineEntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->em->getRepository($this->class);
        }

        return $this->repository;
    }
}

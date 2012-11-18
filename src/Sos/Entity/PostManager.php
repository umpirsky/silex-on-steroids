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
 * Post manager.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class PostManager extends EntityManager
{
    public function __construct(DoctrineEntityManager $em)
    {
        parent::__construct($em, 'Sos\Entity\Post');
    }

    public function persistPost(Post $post)
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    public function findPost($id)
    {
        return $this->getRepository()->find($id);
    }

    public function findPosts()
    {
        return $this->getRepository()->findAll();
    }

    public function countPosts()
    {
        return $this
            ->getRepository()
            ->createQueryBuilder('post')
            ->select('count(post.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function deletePost(Post $post)
    {
        $this->em->remove($post);
        $this->em->flush();
    }
}

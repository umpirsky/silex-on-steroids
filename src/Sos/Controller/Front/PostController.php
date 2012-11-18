<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Controller\Front;

use Sylex\Controller;

/**
 * Post controller.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class PostController extends Controller
{
    public function listAction()
    {
        return $this->get('twig')->render(
            'front/post/list.html.twig',
            array('posts' => $this->get('sos.manager.post')->findPosts())
        );
    }
    
    public function showAction($id)
    {
        return $this->get('twig')->render(
            'front/post/show.html.twig',
            array('post' => $this->get('sos.manager.post')->findPost($id))
        );
    }
}

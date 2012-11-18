<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Controller\Admin;

use Sylex\Controller;

/**
 * Admin index controller.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'admin/index/index.html.twig',
            array('count' => $this->get('sos.manager.post')->countPosts())
        );
    }
}

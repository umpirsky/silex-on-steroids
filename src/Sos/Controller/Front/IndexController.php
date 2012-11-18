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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Index controller.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->container->handle(
            Request::create($this->generateUrl('post_list')),
            HttpKernelInterface::SUB_REQUEST
        );
    }
}

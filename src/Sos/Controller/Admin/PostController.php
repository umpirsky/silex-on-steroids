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
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin post controller.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class PostController extends Controller
{
    public function createAction(Request $request)
    {
        $form = $this->get('sos.form.post');

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->get('sos.manager.post')->persistPost($form->getData());

                return $this->redirect($this->generateUrl('admin_post_show', array('id' => $form->getData()->getId())));
            }
        }

        return $this->render(
            'admin/post/create.html.twig',
            array('form' => $form->createView())
        );
    }

    public function showAction($id)
    {
        return $this->get('twig')->render(
            'admin/post/show.html.twig',
            array('post' => $this->get('sos.manager.post')->findPost($id))
        );
    }

    public function listAction()
    {
        return $this->get('twig')->render(
            'admin/post/list.html.twig',
            array('posts' => $this->get('sos.manager.post')->findPosts())
        );
    }

    public function updateAction(Request $request, $id)
    {
        $form = $this->get('sos.form.post');
        $form->setData($this->get('sos.manager.post')->findPost($id));

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->get('sos.manager.post')->persistPost($form->getData());

                return $this->redirect($this->generateUrl('admin_post_show', array('id' => $id)));
            }
        }

        return $this->get('twig')->render(
            'admin/post/update.html.twig',
            array('form' => $form->createView())
        );
    }

    public function deleteAction($id)
    {
        $this->get('sos.manager.post')->deletePost($this->get('sos.manager.post')->findPost($id));

        return $this->redirect($this->generateUrl('admin_post_list'));
    }
}

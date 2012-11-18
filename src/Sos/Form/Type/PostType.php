<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Form\Type;

use Sos\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Post form type.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'sos.form.label.post.title'
            ))
            ->add('content', 'textarea', array(
                'label' => 'sos.form.label.post.content'
            ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return parent::getDefaultOptions(array(
            'data_class' => 'Sos\Entity\Post',
        ));
    }

    public function getName()
    {
        return 'post';
    }
}

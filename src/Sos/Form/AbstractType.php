<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Form;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
abstract class AbstractType extends BaseAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array_replace(
            array('translation_domain' => 'forms'),
            $options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions(array()));
    }
}

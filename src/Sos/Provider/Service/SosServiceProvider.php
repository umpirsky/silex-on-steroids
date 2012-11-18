<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Provider\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Sos\Entity\PostManager;
use Sos\Form\Type\PostType;

/**
 * Prvides services for SOS.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class SosServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $this->registerManagers($app);
        $this->registerForms($app);
    }

    protected function registerManagers(Application $app)
    {
        $app['sos.manager.post'] = $app->share(function(Application $app) {
            return new PostManager($app['doctrine_orm.em']);
        });
    }

    protected function registerForms(Application $app)
    {
        $app['sos.form.post'] = $app->share(function(Application $app) {
            return  $app['form.factory']->create(
                new PostType()
            );
        });
    }

    public function boot(Application $app)
    {
    }
}

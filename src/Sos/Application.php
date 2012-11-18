<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos;

use Sylex\Application as BaseApplication;
use Umpirsky\Silex\I18nRouting\Provider\I18nRoutingServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Translation\Loader\MoFileLoader;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\Logging\DebugStack;
use Monolog\Logger;
use Assetic\Asset\AssetCache;
use Assetic\Asset\GlobAsset;
use Assetic\Cache\FilesystemCache;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use SilexAssetic\AsseticExtension;
use Palma\Silex\Provider\DoctrineORMServiceProvider;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Sortable\SortableListener;
use BCC\ExtraToolsBundle\Twig\TwigExtension as BccTwigExtension;
use Sos\Provider\Service\SosServiceProvider;
use Sos\Provider\Service\DoctrineExtensionsServiceProvider;
use Sos\Doctrine\Common\Persistance\ManagerRegistry;

/**
 * Application.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class Application extends BaseApplication
{
    public function init()
    {
        $this->registerServiceProviders();
        $this->registerRoutes();
    }

    private function registerServiceProviders()
    {
        $self = $this;

        $this->register(new HttpCacheServiceProvider());
        $this->register(new SessionServiceProvider());
        $this->register(new ValidatorServiceProvider());
        $this->register(new FormServiceProvider());
        $this->share($this->extend('form.extensions', function ($extensions) use ($self) {
            $managerRegistry = new ManagerRegistry(null, array(), array('doctrine_orm.em'), null, null, $self['doctrine_orm.proxies_namespace']);
            $managerRegistry->setContainer($self);
            $extensions[] = new DoctrineOrmExtension($managerRegistry);

            return $extensions;
        }));
//        $this->share($this->extend('form.factory', function (FormFactoryInterface $factory) {
//            $factory->addType(new TypeaheadType());
//
//            return $factory;
//        }));
        $this->register(new UrlGeneratorServiceProvider());
        $this->register(new TranslationServiceProvider(), array(
            'locale' => $this['locale'],
        ));
        $this->register(new I18nRoutingServiceProvider());
        $this->share($this->extend('translator', function($translator, $app) {
            $translator->addLoader('mo', new MoFileLoader());
            foreach (array('messages', 'forms', 'routes') as $domain) {
                $translator->addResource(
                    'mo',
                    sprintf('%s/resources/translations/%s.%s.mo', APPLICATION_ROOT_PATH, $domain, $app['locale']),
                    $app['locale'],
                    $domain
                );
            }

            return $translator;
        }));
        $this->register(new MonologServiceProvider(), array(
            'monolog.logfile' => APPLICATION_ROOT_PATH.'/resources/log/app.log',
            'monolog.name'    => 'app',
            'monolog.level'   => $this['debug'] ? Logger::DEBUG : Logger::WARNING
        ));
        $this->register(new TwigServiceProvider(), array(
            'twig.options'        => array(
                'cache'            => APPLICATION_ROOT_PATH.'/resources/cache/twig',
                'strict_variables' => true
            ),
            'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
            'twig.path'           => array(APPLICATION_ROOT_PATH.'/resources/views')
        ));
        $this->extend('twig', function (\Twig_Environment $twig) use ($self) {
            $twig->addExtension(new BccTwigExtension());

            return $twig;
        });

        $this->register(new SecurityServiceProvider());
        $this->register(new AsseticExtension(), array(
            'assetic.options' => array(
                'debug'            => $this['debug'],
                'auto_dump_assets' => $this['debug'],
            ),
            'assetic.filters' => $this->protect(function($fm) use ($self) {
                $fm->set('yui_css', new CssCompressorFilter(
                    $self['assetic.filter.yui_compressor.path']
                ));
                $fm->set('yui_js', new JsCompressorFilter(
                    $self['assetic.filter.yui_compressor.path']
                ));
            }),
            'assetic.assets' => $this->protect(function($am, $fm) use ($self) {
                $am->set('styles', new AssetCache(
                    new GlobAsset(
                        $self['assetic.input.path_to_css'],
                        //array($fm->get('yui_css'))
                        array()
                    ),
                    new FilesystemCache($self['assetic.path_to_cache'])
                ));
                $am->get('styles')->setTargetPath($self['assetic.output.path_to_css']);

                $am->set('scripts', new AssetCache(
                    new GlobAsset(
                        $self['assetic.input.path_to_js'],
                        //array($fm->get('yui_js'))
                        array()
                    ),
                    new FilesystemCache($self['assetic.path_to_cache'])
                ));
                $am->get('scripts')->setTargetPath($self['assetic.output.path_to_js']);
            })
        ));

        $this->register(new DoctrineORMServiceProvider(), $this['doctrine_orm.options']);
        $this->register(new DoctrineExtensionsServiceProvider(), array(
            'doctrine_orm.extensions' => array(
                new TimestampableListener(),
                new SortableListener(),
            )
        ));

        if ($this['debug']) {
            $logger = new DebugStack();

            $this->extend('doctrine_orm.configuration', function(Configuration $configuration) use ($logger) {
                $configuration->setSQLLogger($logger);

                return $configuration;
            });

            $this->finish(function() use ($self, $logger) {
                foreach ($logger->queries as $query) {
                    $self['monolog']->debug($query['sql'], array('params' => $query['params'], 'types' => $query['types']));
                }
            });
        }

        $this->register(new SosServiceProvider());
    }

    private function registerRoutes()
    {
        $this->get('/', 'Sos\Controller\Front\IndexController::indexAction')
            ->bind('index')
        ;
        $this->get('/post/list', 'Sos\Controller\Front\PostController::listAction')
            ->bind('post_list')
        ;
        $this->get('/post/show/{id}', 'Sos\Controller\Front\PostController::showAction')
            ->bind('post_show')
        ;
        $this->get('/admin', 'Sos\Controller\Admin\IndexController::indexAction')
            ->bind('admin_index')
        ;
        $this->match('/admin/post/create', 'Sos\Controller\Admin\PostController::createAction')
            ->bind('admin_post_create')
        ;
        $this->get('/admin/post/show/{id}', 'Sos\Controller\Admin\PostController::showAction')
            ->bind('admin_post_show')
        ;
        $this->get('/admin/post/list', 'Sos\Controller\Admin\PostController::listAction')
            ->bind('admin_post_list')
        ;
        $this->match('/admin/post/update/{id}', 'Sos\Controller\Admin\PostController::updateAction')
            ->bind('admin_post_update')
        ;
        $this->match('/admin/post/delete/{id}', 'Sos\Controller\Admin\PostController::deleteAction')
            ->bind('admin_post_delete')
        ;
    }
}

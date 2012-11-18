<?php

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        require __DIR__.'/../../src/bootstrap.php';

        unset($app['exception_handler']);
        $app['translator.messages'] = array();

        $app['session.storage'] = $app->share(function() {
            return new MockFileSessionStorage(sys_get_temp_dir());
        });

        return $this->app = $app;
    }

    public function test404()
    {
        $client = $this->createClient();

        $client->request('GET', '/404');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testAdminProtected()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testAdminHome()
    {
        $client = $this->createClient(array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'foo',
        ));
        $client->request('GET', '/admin');

        $this->assertTrue($client->getResponse()->isOK());
    }
}

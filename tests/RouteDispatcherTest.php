<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

class RouteDispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @throws Exception
     */
    public function testDefaultDispatcher()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->get('/', [$this, 'response']);
        $dispatcher = new RouteDispatcher($routeCollection);
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
        $this->assertEquals(200, (string)$response->getStatusCode());
        $this->assertEquals('hello world', (string)$response->getBody());
    }

    public function testAppendMiddleware()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->get('/', [$this, 'response']);
        $dispatcher = new RouteDispatcher($routeCollection);
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
        $this->assertEquals(200, (string)$response->getStatusCode());
        $this->assertEquals('hello world', (string)$response->getBody());
    }

    public function testDispatchRouteName()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->get([
            'name' => 'demo',
            'path' => '/',
        ], [$this, 'response']);
        $dispatcher = new RouteDispatcher($routeCollection);
        $response = $dispatcher->dispatchRoute('demo');
        $this->assertEquals('hello world', (string)$response->getBody());
    }

    public function response()
    {
        return new Response('hello world');
    }
}

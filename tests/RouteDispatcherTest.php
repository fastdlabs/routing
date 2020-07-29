<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use PHPUnit\Framework\TestCase;

class RouteDispatcherTest extends TestCase
{
    public function testDispatchStaticRoute()
    {
        $collections = new RouteCollection();
        $d = new RouteDispatcher($collections);
        $collections->addRoute("GET", "/", "RouteDispatcherTest@routeHandle");
        $response = $d->dispatch(new ServerRequest("GET", '/'));
        echo $response->getBody();
        $this->expectOutputString("test handle");
    }

    public function testDispatchDynamicRoute()
    {
        $collections = new RouteCollection();
        $d = new RouteDispatcher($collections);
        $collections->addRoute("GET", "/{name}", "RouteDispatcherTest@routeDyHandle");
        $response = $d->dispatch(new ServerRequest("GET", '/foo'));
        echo $response->getBody();
    }

    public function routeHandle()
    {
        return new Response("test handle");
    }

    public function routeDyHandle(ServerRequest $request)
    {
        return new Response("hello {$request->getAttribute('name')}");
    }
}

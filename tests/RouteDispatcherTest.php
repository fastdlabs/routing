<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

class RouteDispatcherTest extends TestCase
{
    public function testDispatcher()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->get('/', function (ServerRequest $request, Delegate $delegate) {
            echo 'hello world';
        });
        $dispatcher = new RouteDispatcher($routeCollection, [
            new BeforeMiddleware(),
            new AfterMiddleware(),
        ]);
        $dispatcher->dispatch($this->createRequest('GET', '/'));
        $this->expectOutputString(<<<EOF
before
after
hello world
EOF
);
    }

    public function testDispatcherRouteParams()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->get('/{name}', function (ServerRequest $request, Delegate $delegate) {
            echo 'hello ' . $request->getAttribute('name');
        });
        $dispatcher = new RouteDispatcher($routeCollection);
        $dispatcher->dispatch($this->createRequest('GET', '/foo'));
        $this->expectOutputString('hello foo');
    }
}

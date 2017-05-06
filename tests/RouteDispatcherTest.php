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
        })->withMiddleware('default');
        $dispatcher = new RouteDispatcher($routeCollection, ['default' => [
            new BeforeMiddleware(),
            new AfterMiddleware(),
        ]]);
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

    public function testDispatcherMiddlewareBreaker()
    {
        $routeCollection = new RouteCollection();

        $routeCollection->get('/{name}', function (ServerRequest $request, Delegate $delegate) {
            return new Response('hello ' . $request->getAttribute('name'));
        })->withAddMiddleware(new BreakerMiddleware());

        $dispatcher = new RouteDispatcher($routeCollection);
        $response = $dispatcher->dispatch($this->createRequest('GET', '/break'));
        $this->assertEquals('break', $response->getBody());
        $response = $dispatcher->dispatch($this->createRequest('GET', '/foo'));
        $this->assertEquals('hello foo', $response->getBody());
    }

    public function testDispatcherCallMiddleware()
    {
        $routeCollection = new RouteCollection();
        $routeCollection
            ->get('/{name}', function (ServerRequest $request, Delegate $delegate) {
                return new Response('hello ' . $request->getAttribute('name'));
            })
            ->withAddMiddleware(new AfterMiddleware())
            ->withAddMiddleware(new BeforeMiddleware())
            ->withAddMiddleware(new DefaultMiddleware())
        ;
        $dispatcher = new RouteDispatcher($routeCollection);
        $dispatcher->dispatch($this->createRequest('GET', '/foo'));
        $this->expectOutputString(<<<EOF
after
before
default
EOF
);
    }

    public function testDispatcherCollectionGroupMiddleware()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->group('/demo', function (RouteCollection $routeCollection) {
            $routeCollection->get('/{name}', function (ServerRequest $request, Delegate $delegate) {
                echo 'hello ' . $request->getAttribute('name');
            });
        }, ['default']);
        $dispatcher = new RouteDispatcher($routeCollection, ['default' => [
            new BeforeMiddleware(),
            new AfterMiddleware(),
        ]]);
        $dispatcher->dispatch($this->createRequest('GET', '/demo/world'));
        $this->expectOutputString(<<<EOF
before
after
hello world
EOF
        );
    }
}

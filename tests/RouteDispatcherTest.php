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

class RouteDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/middleware/AfterMiddleware.php';
        include_once __DIR__ . '/middleware/BreakerMiddleware.php';
        include_once __DIR__ . '/middleware/BeforeMiddleware.php';
        include_once __DIR__ . '/middleware/DefaultMiddleware.php';
    }

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
        $dispatcher->dispatch(new ServerRequest('GET', '/'));
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
        $dispatcher->dispatch(new ServerRequest('GET', '/foo'));
        $this->expectOutputString('hello foo');
    }

    public function testDispatcherMiddlewareBreaker()
    {
        $routeCollection = new RouteCollection();

        $routeCollection->get('/{name}', function (ServerRequest $request, Delegate $delegate) {
            return new Response('hello ' . $request->getAttribute('name'));
        })->withAddMiddleware(new BreakerMiddleware());

        $dispatcher = new RouteDispatcher($routeCollection);
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/break'));
        $this->assertEquals('break', $response->getBody());
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/foo'));
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
        $dispatcher->dispatch(new ServerRequest('GET', '/foo'));
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
        $routeCollection->group('/foo', function (RouteCollection $routeCollection) {
            $routeCollection->get('/{name}', function (ServerRequest $request) {
                echo 'hello ' . $request->getAttribute('name');
            });
        }, ['default']);
        $dispatcher = new RouteDispatcher($routeCollection, ['default' => [
            new BeforeMiddleware(),
            new AfterMiddleware(),
        ]]);
        $dispatcher->dispatch(new ServerRequest('GET', '/foo/bar'));
        $this->expectOutputString(<<<EOF
before
after
hello bar
EOF
        );
    }
}

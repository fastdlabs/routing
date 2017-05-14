<?php
use FastD\Middleware\DelegateInterface;
use FastD\Routing\Resource\ResourceInterface;
use FastD\Routing\Route;
use FastD\Routing\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

class Resource extends ResourceInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        return new \FastD\Http\Response('hello world');
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function data(ServerRequestInterface $request)
    {
        // TODO: Implement data() method.
    }
}

class ResourceRouteTest extends PHPUnit_Framework_TestCase
{
    public function testResourceRoute()
    {
        $route = new Route('GET', '/', Resource::class);
        $middleware = new RouteMiddleware($route);
        $dispatcher = new \FastD\Middleware\Dispatcher();
        $dispatcher->after($middleware);
        echo $dispatcher->dispatch(new \FastD\Http\ServerRequest('GET', '/'))->getBody();
        $this->expectOutputString('hello world');
    }

    public function testResourceCollection()
    {
        $collection = new \FastD\Routing\RouteCollection();
        $collection->resource('/', Resource::class);
    }
}

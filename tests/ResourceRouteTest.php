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

class Resource extends \FastD\Routing\Resource\AbstractCreateResource
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
}

class ResourceRouteTest extends PHPUnit_Framework_TestCase
{
    public function testResourceRoute()
    {
        $route = new Route('GET', '/', Resource::class);
        $middleware = new RouteMiddleware($route);
        $dispatcher = new \FastD\Middleware\Dispatcher();
        $dispatcher->after($middleware);
        $response = $dispatcher->dispatch(new \FastD\Http\ServerRequest('GET', '/'));
        echo $response->getBody();
        $this->expectOutputString('hello world');
        $this->assertEquals(\FastD\Http\Response::HTTP_OK, $response->getStatusCode());
    }
}

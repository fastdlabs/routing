<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Http\ServerRequest;
use FastD\Routing\Route;
use FastD\Routing\RouteCollection;
use PHPUnit\Framework\TestCase;

class RouteCollectionTest extends TestCase
{
    /**
     * @var RouteCollection
     */
    protected RouteCollection $routeCollection;

    /**
     * @var Route
     */
    protected Route $route;

    public function setUp()
    {
        $this->routeCollection = new RouteCollection();
        $this->route = new Route('GET', '/', null);
    }

    public function testAddRouteAndMatchRoute()
    {
        $this->routeCollection->addRoute('GET', '/', 'test_api')
            ->setName('test_api')
            ->setParameters(['foo' => 'bar'])
        ;
        $route = $this->routeCollection->match(new ServerRequest('GET', '/'));
        $this->assertEquals(['foo' => 'bar'], $route->getParameters());
        $this->assertEquals('test_api', $route->getName());
    }

    public function testRouteCollectionPrefix()
    {
        /**
         * 单独设置 prefix，middleware 会影响全局 prefix，因此如要单独设置的话，需要在下次设置的时候进行参数重置
         * RouteCollection::restoreMiddleware() OR RouteCollection::restorePrefix()
         */
        $this->routeCollection->prefix('/api')->addRoute('GET', '/', 'test_api');
        $this->routeCollection->addRoute('GET', '/foo', 'test_api');
        $route = $this->routeCollection->match(new ServerRequest('GET', '/api/'));
        $route2 = $this->routeCollection->match(new ServerRequest('GET', '/api'));
        $route3 = $this->routeCollection->match(new ServerRequest('GET', '/api/foo'));
        $this->assertEquals($route, $route2);
        $this->assertEquals('/api/foo', $route3->getPath());
    }

    public function testRouteCollectionGroup()
    {
        $this->routeCollection->prefix('/api')->group(function (RouteCollection $routeCollection) {
            $routeCollection->addRoute('GET', '/', 'test_api')->setName('test_api');
            $routeCollection->addRoute('POST', '/', 'test_api');
        });
        $route = $this->routeCollection->match(new ServerRequest('GET', '/api/'));
        $route2 = $this->routeCollection->match(new ServerRequest('POST', '/api'));
        $this->assertEquals('test_api', $route->getName());
        $this->assertEquals('POST', $route2->getMethod());
    }

    public function testRouteCollectionMiddleware()
    {
        $this->routeCollection->middleware('foo')->group(function (RouteCollection $routeCollection) {
            $routeCollection->addRoute('GET', '/', 'test_api')->setName('test_api');
        });

        $this->routeCollection->addRoute('POST', '/', 'test_api')->setName('test_api');

        $route = $this->routeCollection->match(new ServerRequest('POST', '/'));

        $this->assertEquals('POST', $route->getMethod());
    }
}

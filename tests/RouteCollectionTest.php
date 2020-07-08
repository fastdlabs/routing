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
    }

    public function testAddStaticRoute()
    {
        $this->routeCollection
            ->addRoute('GET', '/', '')
            ->setName('test_api')
            ->setParameters(['foo' => 'bar'])
        ;

        $this->assertTrue(true);
    }

    public function testMiddlewareRoute()
    {
        $this->routeCollection
            ->middleware(DefaultMiddleware::class)
            ->group(function (RouteCollection $routeCollection) {
                $routeCollection->get('/', function () {});
            })
        ;
        $this->routeCollection->post('/', function () {});

        $route = $this->routeCollection->aliasMap['GET']['/'];

        $this->assertEquals('/', $route->getPath());
        $this->assertEquals([DefaultMiddleware::class], $route->getMiddleware());
        $this->assertEmpty($this->routeCollection->aliasMap['POST']['/']->getMiddleware());
    }

    public function testPrefixRoute()
    {
        $this->routeCollection
            ->prefix('/api')
            ->group(function (RouteCollection $routeCollection) {
                $routeCollection->get('/', function () {});
            })
        ;

        $this->routeCollection->post('/', function () {});
        $route = $this->routeCollection->aliasMap['GET']['/api/'];

        $this->assertEquals('/api', $route->getPath());
        $this->assertEquals('/', $this->routeCollection->aliasMap['POST']['/']->getPath());
    }

    public function testMatch()
    {
        $this->routeCollection
            ->prefix('/api')
            ->group(function (RouteCollection $routeCollection) {
                $routeCollection->get('/', function () {});
            })
        ;

        $this->routeCollection->post('/', function () {});
        $route = $this->routeCollection->match(new ServerRequest("GET", "/api"));
        $this->assertEquals('/api', $route->getPath());
        $route = $this->routeCollection->getActiveRoute();
        $this->assertEquals('/api', $route->getPath());
    }
}

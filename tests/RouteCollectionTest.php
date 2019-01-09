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
    protected $routeCollection;

    /**
     * @var Route
     */
    protected $route;

    public function setUp()
    {
        $this->routeCollection = new RouteCollection();
        $this->route = new Route('GET', '/', null);
    }

    public function testAddRoute()
    {
        $route = new Route('GET', '/', null);
        $this->routeCollection->addRoute('demo', $route);
        $route = $this->routeCollection->getRoute('demo');
        $this->assertInstanceOf(Route::class, $route);
    }

    public function testPrefixAddRoute()
    {
        $this->routeCollection->prefix('/api')->addRoute('demo', $this->route);

        $route = $this->routeCollection->getRoute('demo');

        print_r($route);
    }

    public function testMatchRoute()
    {
        $route = new Route('GET', '/', null);
        $this->routeCollection->addRoute('demo.get', $route);
        $route = new Route('POST', '/', null);
        $this->routeCollection->addRoute('demo.post', $route);
        $route = $this->routeCollection->match(new ServerRequest('GET', '/'));
        $this->assertEquals('GET', $route->getMethod());
        $route = $this->routeCollection->match(new ServerRequest('POST', '/'));
        $this->assertEquals('POST', $route->getMethod());
    }
}

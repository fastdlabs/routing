<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Route;
use FastD\Routing\RouteCollection;

class RouteCacheTest extends \PHPUnit_Framework_TestCase
{
    protected $collections;

    public function setUp()
    {
        $collections = new RouteCollection(__DIR__);

        if (!$collections->cache->hasCache()) {
            $collections->addRoute('GET', '/', 'tests');
            $collections->addRoute('GET', '/{id}', 'tests@');
        }

        $this->collections = $collections;
    }

    public function testToCache()
    {
        if (!$this->collections->cache->hasCache()) {
            $this->collections->caching();
        }
    }

    public function testLoadCache()
    {
        $this->assertEquals(1, count($this->collections->staticRoutes));
        $this->assertEquals(1, count($this->collections->dynamicRoutes));

        $route = $this->collections->match('GET', '/1');

        $this->assertInstanceOf(Route::class, $route);
    }

    public function testRouteCacheGenerateUrl()
    {
        $routeCollection = new RouteCollection(__DIR__);

        $this->assertEquals('/', $routeCollection->generateUrl('/'));
        $this->assertEquals('/.html', $routeCollection->generateUrl('/', [], 'html'));
        $this->assertEquals('/.html', $routeCollection->generateUrl('/', ['foo' => 'bar'], 'html'));
    }
}

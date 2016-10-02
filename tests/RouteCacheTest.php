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
    public function testToCache()
    {
        $collections = new RouteCollection(__DIR__);

        $collections->addRoute('/', 'tests');
        $collections->addRoute('/{id}', 'tests@');

        $collections->caching();
    }

    public function testLoadCache()
    {
        $collections = new RouteCollection(__DIR__);

        $this->assertEquals(1, count($collections->staticRoutes));
        $this->assertEquals(1, count($collections->dynamicRoutes));

        $route = $collections->match('ANY', '/1');

        $this->assertInstanceOf(Route::class, $route);
    }
}

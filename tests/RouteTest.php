<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Routing\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testStaticRoute()
    {
        $route = new Route('GET', '/test', []);
        $this->assertEquals('GET', $route->getMethod());
        $this->assertEquals('/test', $route->getPath());
        $this->assertEmpty($route->getParameters());
        $this->assertNull($route->getRegex());
    }

    public function testDynamicRouteRequireVariables()
    {
        $route = new Route('GET', '/users/{name}', []);
        $regex = '~^(' . $route->getRegex() . ')$~';
        $this->assertRegExp($regex, '/users/10');
    }

    public function testRouteIsStatic()
    {
        $route = new Route('GET', '/foo/*', []);
        $this->assertFalse($route->isStaticRoute());
    }

    public function testRouteFuzzyMatching()
    {
        $route = new Route('GET', '/foo/*', []);
        $regex = '~^(' . $route->getRegex() . ')$~';
        $this->assertRegExp($regex, '/foo/10');
        $this->assertRegExp($regex, '/foo/bar');
    }
}

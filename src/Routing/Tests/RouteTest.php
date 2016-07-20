<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new Route('test', 'GET', '/test', []);

        $this->assertEquals('GET', $route->getMethod());

        $this->assertEquals('/test', $route->getPath());

        $this->assertNull($route->getRegex());
    }

    public function testDynamicRouteOptionalVariables()
    {
        $route = new Route('test', 'GET', '/users/[{name}]', []);

        $regex = '~^(' . $route->getRegex() . ')$~';

        $this->assertRegExp($regex, '/users/10');
        $this->assertRegExp($regex, '/users');
        $this->assertRegExp($regex, '/users/');
    }

    public function testDynamicRouteRequireVariables()
    {
        $route = new Route('test', 'GET', '/users/{name}', []);

        $regex = '~^(' . $route->getRegex() . ')$~';

        $this->assertRegExp($regex, '/users/10');
    }
}

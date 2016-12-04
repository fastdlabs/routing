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

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new Route('GET', '/test', []);

        $this->assertEquals('GET', $route->getMethod());

        $this->assertEquals('/test', $route->getPath());

        $this->assertNull($route->getRegex());
    }

    public function testDynamicRouteRequireVariables()
    {
        $route = new Route('GET', '/users/{name}', []);

        $regex = '~^(' . $route->getRegex() . ')$~';

        $this->assertRegExp($regex, '/users/10');
    }

}

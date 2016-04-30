<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 上午10:53
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new Route('GET', '/', function () {
            return 'hello world';
        });

        $this->assertEquals('/', $route->getPath());
        $this->assertEquals('/^\/{0,1}$/', $route->getPathRegex());
        $this->assertRegExp($route->getPathRegex(), '/');
        $this->assertRegExp($route->getPathRegex(), '');
    }

    public function testRegex()
    {
        $route = new Route('GET', '/name', function () {});
        $this->assertRegExp($route->getPathRegex(), '/name');

        $route = new Route('GET', '/{name}', function () {});
        $this->assertRegExp($route->getPathRegex(), '/abc');
        $this->assertRegExp($route->getPathRegex(), '/bbc');

        $route = new Route('GET', '/{age}', null, ['age' => 13]);
        $this->assertEquals(['age' => 13], $route->getDefaults());
        $this->assertEquals(['age' => 13], $route->getParameters());
    }
}

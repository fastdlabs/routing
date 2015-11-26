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
        $route = new Route('/', function () {
            return 'hello world';
        });

        $this->assertEquals('/', $route->getPath());
        $this->assertEquals('/^\/{0,1}$/', $route->getPathRegex());

        $route = new Route('/name', function () {}, [], [], [], ['http', 'https'], 'www.baidu.com');

        $this->assertEquals('/name', $route->getPath());
        $this->assertEquals(['http', 'https'], $route->getSchema());
        $this->assertEquals('www.baidu.com', $route->getHost());
    }

    public function testParametersRoute()
    {
        $route = new Route('/{name}', function ($name) {});

        $this->assertEquals(['name' => null], $route->getParameters());

        $route = new Route('/{name}', function () {}, ['name' => 'janhuang']);
        $this->assertEquals(['name' => 'janhuang'], $route->getParameters());

        $route = new Route('/{name}/{age}', function () {}, ['age' => 18]);
        $this->assertEquals(['name' => null, 'age' => 18], $route->getParameters());
        $this->assertEquals('/^\/{1}(?P<name>.+)\/{1}(?P<age>.+)$/', $route->getPathRegex());
        $this->assertRegExp($route->getPathRegex(), '/janhuang/18');
    }
}

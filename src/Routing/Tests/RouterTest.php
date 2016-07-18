<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/17
 * Time: 下午11:44
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRoute()
    {
        $router = new Router();

        $router->addRoute('GET', '/test', []);

        $router->addRoute('POST', '/test/b', []);

        $router->addRoute('GET', '/test/{id}', []);

        $router->addRoute('GET', '/test/{b}', []);
    }

    public function testMatch()
    {
        $router = new Router();

        $router->addRoute('GET', '/test', []);

        $route = $router->match('GET', '/test');

        $this->assertEquals('/test', $route->getPath());

        $router->addRoute('GET', '/{test}', []);

        $route = $router->match('GET', '/abc');

        $this->assertEquals('GET', $route->getMethod());

        $this->assertEquals('/{test}', $route->getPath());
    }
}

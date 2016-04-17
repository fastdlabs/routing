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
    /**
     * @var Router
     */
    protected $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testAddRoute()
    {
        $this->router->addRoute('GET', '/test');

        $this->assertEquals([
            '/' => [
                'test:get'
            ]
        ], $this->router->getGroup());

        $this->router->addRoute('GET', '/name');

        $this->assertEquals([
            '/' => [
                'test:get',
                'name:get'
            ]
        ], $this->router->getGroup());

        $this->router->group('/base', function () {
            $this->router->addRoute('GET', '/name');
        });

        $this->assertEquals([
            '/' => [
                'test:get',
                'name:get'
            ],
            '/base' => [
                'base_name:get'
            ]
        ], $this->router->getGroup());

        $this->router->group('/base', function () {
            $this->router->group('/second', function () {
                $this->router->addRoute('GET', '/name');
            });
        });

        $this->assertEquals([
            '/' => [
                'test:get',
                'name:get'
            ],
            '/base' => [
                'base_name:get'
            ],
            '/base/second' => [
                'base_second_name:get'
            ]
        ], $this->router->getGroup());

        $route = $this->router->getCurrentRoute();

        $this->assertEquals('/base/second/name', $route->getPath());

        $this->assertRegExp($route->getPathRegex(), '/base/second/name');
    }

    public function testRouteDiffMethod()
    {
        $this->router->addRoute('GET', '/test');

        $this->assertEquals($this->router->getRoute('test:get'), $this->router->getCurrentRoute());

        $this->assertEquals($this->router->getRoute('test:get')->getName(), 'test:get');

        $this->router->addRoute('POST', '/test');

        $this->assertEquals([
            '/' => [
                'test:get',
                'test:post'
            ]
        ], $this->router->getGroup());

        $this->assertEquals($this->router->getRoute('test:get')->getMethod(), 'GET');
        $this->assertEquals($this->router->getRoute('test:post')->getMethod(), 'POST');

        $this->router->addRoute('PUT', '/test');

        $this->assertEquals($this->router->getRoute('test:put')->getMethod(), 'PUT');
    }

    public function testRouterDispatch()
    {
        $this->router->addRoute('GET', '/test', function () {
            return 'hello world';
        });

        $callback = $this->router->dispatch('GET', '/test');

        $this->assertEquals($callback(), 'hello world');

        $this->router->addRoute('GET', '/{name}', function () {
            return 'hello jan';
        });

        $callback = $this->router->dispatch('GET', '/janhuang');

        $this->assertEquals($callback(), 'hello jan');
    }
}

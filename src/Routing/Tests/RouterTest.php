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
        $this->router->addRoute('test:get', 'GET', '/test');

        $this->assertEquals([
            '/' => [
                'test:get'
            ]
        ], $this->router->getGroup());

        $this->router->addRoute('name:get', 'GET', '/name');

        $this->assertEquals([
            '/' => [
                'test:get',
                'name:get'
            ]
        ], $this->router->getGroup());

        $this->router->group('/base', function () {
            $this->router->addRoute('base_name:get', 'GET', '/name');
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
                $this->router->addRoute('base_second_name:get', 'GET', '/name');
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
        $this->router->addRoute('test:get', 'GET', '/test');

        $this->assertEquals($this->router->getRoute('test:get'), $this->router->getCurrentRoute());

        $this->assertEquals($this->router->getRoute('test:get')->getName(), 'test:get');

        $this->router->addRoute('test:post', 'POST', '/test');

        $this->assertEquals([
            '/' => [
                'test:get',
                'test:post'
            ]
        ], $this->router->getGroup());

        $this->assertEquals($this->router->getRoute('test:get')->getMethod(), 'GET');
        $this->assertEquals($this->router->getRoute('test:post')->getMethod(), 'POST');

        $this->router->addRoute('test:put', 'PUT', '/test');

        $this->assertEquals($this->router->getRoute('test:put')->getMethod(), 'PUT');
    }

    public function testRouterDispatch()
    {
        $this->router->addRoute('test:get', 'GET', '/test', function () {
            return 'hello world';
        });

        $route = $this->router->dispatch('GET', '/test');

        $this->assertEquals($route->getCallback()(), 'hello world');

        $this->router->addRoute('name:get', 'GET', '/{name}', function () {
            return 'hello jan';
        });

        $route = $this->router->dispatch('GET', '/janhuang');

        $this->assertEquals($route->getCallback()(), 'hello jan');
    }

    public function testGenerate()
    {
        $this->router->addRoute('test:get', 'GET', '/test', function () {
            return 'hello world';
        });

        $this->assertEquals($this->router->generateUrl('test:get'), '/test');

        $this->router->addRoute('name:get', 'GET', '/{name}')->setFormats(['json', 'php']);

        $this->assertEquals('/test', $this->router->generateUrl('name:get', ['name' => 'test']));

        $this->assertEquals('/janhuang', $this->router->generateUrl('name:get', ['name' => 'janhuang']));
        $this->assertEquals('/janhuang.json', $this->router->generateUrl('name:get', ['name' => 'janhuang'], 'json'));
    }
}

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
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteNotFoundException;

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
        $collection = new RouteCollection();

        $collection->addRoute('test', 'GET', '/users/[{name}]', function ($name) {
            return $name;
        });

        $regex = '~^(' . $collection->getRoute('test')->getRegex() . ')$~';

        $this->assertRegExp($regex, '/users/10');
        $this->assertRegExp($regex, '/users');
        $this->assertRegExp($regex, '/users/');

        $response = $collection->dispatch('GET', '/users');

        $this->assertEquals('', $response);

        $collection->addRoute('test2', 'GET', '/[{name}]', function ($name) {
            return $name;
        }, ['name' => 'janhuang']);

        $this->assertEquals('janhuang', $collection->dispatch('GET', '/'));
    }

    public function testDynamicRouteRequireVariables()
    {
        $route = new Route('test', 'GET', '/users/{name}', []);

        $regex = '~^(' . $route->getRegex() . ')$~';

        $this->assertRegExp($regex, '/users/10');
    }

    /**
     * @expectedException \FastD\Routing\RouteNotFoundException
     */
    public function testRouteCallbackParameters()
    {
        $collection = new RouteCollection();

        $collection->addRoute('test', 'GET', '/user/{name}', function ($name) {
            return $name;
        }, ['name' => 'jan']);

        $collection->addRoute('test2', 'GET', '/user/{name}/{age}', function ($name, $age) {
            return $name . $age;
        }, ['name' => 'janhuang']);

        $route = $collection->match('GET', '/user/test2');

        $this->assertEquals([
            'name' => 'test2'
        ], $route->getParameters());

        $route = $collection->match('GET', '/user/test2/123');

        $this->assertEquals([
            'name' => 'test2',
            'age' => 123
        ], $route->getParameters());


        $this->assertEquals('janhuang', $collection->dispatch('GET', '/user/janhuang'));

        $this->assertEquals('janhuang11', $collection->dispatch('GET', '/user/janhuang/11'));

        $collection->addRoute('test2', 'GET', '/profile/{name}/{age}', function ($name, $age) {
            return $name . $age;
        }, ['name' => 'janhuang']);

        $this->assertEquals('janhuang', $collection->dispatch('GET', '/profile/janhuang'));
    }
}

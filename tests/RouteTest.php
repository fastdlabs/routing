<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Exceptions\RouteNotFoundException;
use FastD\Routing\Route;
use FastD\Routing\RouteCollection;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new Route('/test', [], 'test', 'GET');

        $this->assertEquals('GET', $route->getMethod());

        $this->assertEquals('/test', $route->getPath());

        $this->assertNull($route->getRegex());
    }

    /**
     * @expectedException \Exception
     */
    public function testDynamicRouteOptionalVariablesNotDefaultValues()
    {
        $collection = new RouteCollection();

        $collection->addRoute('/users/[{name}]', function ($name) {
            return $name;
        }, 'test', 'GET');

        try {
            $collection->dispatch('GET', '/users');
        } catch (\Exception $e) {
            throw new \Exception('damn it');
        }
    }

    public function testDynamicRouteOptionalVariablesHasDefaultValue()
    {
        $collection = new RouteCollection();

        $collection->addRoute('/users/[{name}]', function ($name = null) {
            return $name;
        }, 'test', 'GET');

        $this->assertEquals('', $collection->dispatch('GET', '/users'));

        $collection->addRoute('/[{name}]', function ($name) {
            return $name;
        }, 'test2', 'GET',  ['name' => 'janhuang']);

        $this->assertEquals('janhuang', $collection->dispatch('GET', '/'));
    }

    public function testDynamicRouteRequireVariables()
    {
        $route = new Route('/users/{name}', [], 'test', 'GET');

        $regex = '~^(' . $route->getRegex() . ')$~';

        $this->assertRegExp($regex, '/users/10');
    }

    /**
     * @expectedException \FastD\Routing\Exceptions\RouteNotFoundException
     */
    public function testRouteCallbackParameters()
    {
        $collection = new RouteCollection();

        $collection->addRoute('/user/{name}', function ($name) {
            return $name;
        }, 'test', 'GET', ['name' => 'jan']);

        $collection->addRoute('/user/{name}/{age}', function ($name, $age) {
            return $name . $age;
        }, 'test2', 'GET', ['name' => 'janhuang']);

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

        $collection->addRoute('/profile/{name}/{age}', function ($name, $age) {
            return $name . $age;
        }, 'test2', 'GET', ['name' => 'janhuang']);

        $this->assertEquals('janhuang', $collection->dispatch('GET', '/profile/janhuang'));
    }
}

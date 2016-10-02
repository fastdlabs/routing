<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;

use FastD\Routing\RouteCollection;
use FastD\Routing\Router;

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRouteToCollection()
    {
        $collection = new RouteCollection();

        $collection->addRoute('/test', [], 'test1', 'GET');
        $collection->addRoute('/test/{name}', [], 'test2', 'GET');
        $collection->addRoute('/user/profile/{name}', function ( ) {
            return 'get profile';
        }, 'test3', 'GET');
        $collection->addRoute('/user/profile/{name}', function () {
            return 'post profile';
        }, 'user', 'POST');

        $route = $collection->match('GET', '/user/profile/janhuang');

        $this->assertEquals('get profile', call_user_func_array($route->getCallback(), []));

        $route = $collection->match('POST', '/user/profile/janhuang');

        $this->assertEquals('post profile', call_user_func_array($route->getCallback(), []));
    }

    public function testAddRouteToCollectionGroup()
    {
        $collection = new RouteCollection();

        $collection->group('/user', function (RouteCollection $collection) {
            $collection->addRoute('/test', function () {
                return 'hello collection group';
            }, 'test1', 'GET');
        });

        $route = $collection->match('GET', '/user/test');

        $this->assertEquals('hello collection group', call_user_func_array($route->getCallback(), []));
    }

    public function testRouteMatch()
    {
        $collection = new RouteCollection();

        for ($i = 0; $i < 15; $i++) {
            $collection->addRoute('/test_' . $i, function () use ($i) {
                return $i;
            }, 'test' . $i, 'GET');
        }

        $route = $collection->match('GET', '/test_1');

        $this->assertEquals(1, call_user_func_array($route->getCallback(), []));

        $route = $collection->match('GET', '/test_10');

        $this->assertEquals(10, call_user_func_array($route->getCallback(), []));

        $route = $collection->match('GET', '/test_13');

        $this->assertEquals(13, call_user_func_array($route->getCallback(), []));
    }

    public function testGenerateUrl()
    {
        $collection = new RouteCollection();

        $collection->addRoute('/user/profile', function () {

        }, 'static1', 'GET');

        $this->assertEquals('/user/profile', $collection->generateUrl('static1'));

        $collection->addRoute('/user/{name}/profile', function ($name) {
            return $name;
        }, 'test1', 'GET');

        $path = $collection->generateUrl('test1', ['name' => 'janhuang']);

        $this->assertEquals('/user/janhuang/profile', $path);

        $collection->addRoute('/articles/[{id}]', function ($name) {
            return $name;
        }, 'test2', 'GET');

        $path = $collection->generateUrl('test2');

        $this->assertEquals('/articles', $path);

        $path = $collection->generateUrl('test2', ['id' => '10']);

        $this->assertEquals('/articles/10', $path);

        $regex = '~^(' . $collection->getRoute('test2')->getRegex() . ')$~';

        $this->assertRegExp($regex, '/articles/10');
        $this->assertRegExp($regex, '/articles');
    }

    public function testDynamicRouteMatch()
    {
        $collection = new RouteCollection();

        $collection->addRoute('/user/{user}', function ($user) {
            return $user;
        }, 'profile', 'GET');
        $collection->addRoute('/user/{user}/{age}', function ($user, $age) {
            return $user . $age;
        }, 'profile.age', 'GET');

        $this->assertEquals('jan', $collection->dispatch('GET', '/user/jan'));
        $this->assertEquals('jan18', $collection->dispatch('GET', '/user/jan/18'));
    }

    public function testInit()
    {
        $collections = new RouteCollection();
    }
}

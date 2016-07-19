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

use FastD\Routing\RouteCollection;
use FastD\Routing\Router;

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRouteToCollection()
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/test', []);
        $collection->addRoute('GET', '/test/{name}', []);
        $collection->addRoute('GET', '/user/profile/{name}', function ( ) {
            return 'get profile';
        });
        $collection->addRoute('POST', '/user/profile/{name}', function () {
            return 'post profile';
        });

        $route = $collection->match('GET', '/user/profile/janhuang');

        $this->assertEquals('get profile', call_user_func_array($route->getCallback(), []));

        $route = $collection->match('POST', '/user/profile/janhuang');

        $this->assertEquals('post profile', call_user_func_array($route->getCallback(), []));
    }

    public function testAddRouteToCollectionGroup()
    {
        $collection = new RouteCollection();

        $collection->group('/user', function (RouteCollection $collection) {
            $collection->addRoute('GET', '/test', function () {
                return 'hello collection group';
            });
        });

        $route = $collection->match('GET', '/user/test');

        $this->assertEquals('hello collection group', call_user_func_array($route->getCallback(), []));
    }

    public function testRouteMatch()
    {
        $collection = new RouteCollection();

        for ($i = 0; $i < 15; $i++) {
            $collection->addRoute('GET', '/test_' . $i, function () use ($i) {
                return $i;
            });
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

        $collection->addRoute('GET', '/user/{name}/profile', function ($name) {
            return $name;
        });


    }
}

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

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteCollection
     */
    protected $collection;

    public function setUp()
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/test', []);
        $collection->addRoute('GET', '/test/{name}', []);
        $collection->addRoute('GET', '/user/profile/{name}', []);
        $collection->addRoute('POST', '/user/profile/{name}', []);
        $collection->addRoute('ANY', '/user/email/{name}', []);

        $this->collection = $collection;
    }

    public function testAddRouteToCollection()
    {
        $this->assertEquals(count($this->collection->staticRoutes), 1);
        $this->assertEquals(count($this->collection->dynamicRoutes), 3);
        $this->assertEquals(count($this->collection->dynamicRoutes['GET'][0]['routes']), 2);
        $this->assertEquals(count($this->collection->dynamicRoutes['POST'][0]['routes']), 1);
    }

    public function testRouteGetMethodMatch()
    {
        $route = $this->collection->match('GET', '/test');

        $this->assertEquals('GET', $route->getMethod());
        $this->assertNull($route->getName());
        $this->assertTrue($route->isStaticRoute());
    }

    public function testRoutePostMethodMatch()
    {
        $route = $this->collection->match('POST', '/user/profile/jan');

        $this->assertEquals('POST', $route->getMethod());
        $this->assertNull($route->getName());
        $this->assertFalse($route->isStaticRoute());
        $this->assertEquals(['name' => 'jan'], $route->getParameters());
        $this->assertEquals(['name'], $route->getVariables());
    }

    public function testRouteAnyMethodMatch()
    {
        $route = $this->collection->match('POST', '/user/email/jan');

        $this->assertEquals('POST', $route->getMethod());
    }

    public function testRouteGenerator()
    {
        $url = $this->collection->generateUrl('/user/email/{name}', ['name' => 'jan']);

        $this->assertEquals('/user/email/jan', $url);
    }

    public function testRouteGeneratorHasSuffix()
    {
        $url = $this->collection->generateUrl('/user/email/{name}', ['name' => 'jan'], 'html');

        $this->assertEquals('/user/email/jan.html', $url);
    }

    public function testDynamicRouteOptionalVariablesNotDefaultValues()
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/users/[{name}]', function ($name) {
            return $name;
        });

        try {
            $collection->match('GET', '/users');
        } catch (\Exception $e) {
            throw new \Exception('damn it');
        }
    }

    public function testDynamicRouteOptionalVariablesHasDefaultValue()
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/users/[{name}]', function ($name = null) {
            return $name;
        });

        $collection->addRoute('GET', '/[{name}]', function ($name) {
            return $name;
        },  ['name' => 'janhuang']);

    }
}

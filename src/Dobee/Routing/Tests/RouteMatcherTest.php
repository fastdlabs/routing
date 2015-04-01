<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/31
 * Time: 下午11:39
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Matcher\RouteMatcher;
use Dobee\Routing\RouteCollections;
use Dobee\Routing\Route;
use Dobee\Routing\RouteException;

class RouteMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteCollections
     */
    public $collection;

    public function setUp()
    {
        $this->collection = new RouteCollections();

        $this->collection->setRoute(new Route('/demo/{name}', 'demo', array('name' => 'jan'), array('ANY')));
        $this->collection->setRoute(new Route('/test/{name}/{age}', 'test', array('name' => 'jan', 'age' => 22)));
        $this->collection->setRoute(new Route('/method', 'method', array(), array('GET', 'POST', 'ANY'), array(), array('json', 'xml', 'php')));
    }

    public function testMatchRoute()
    {
        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/demo', $this->collection));
        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/demo/jan', $this->collection));
        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/demo/jan.json', $this->collection));

        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/test/jan/12', $this->collection));
        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/test/jan/22', $this->collection));
        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/test/jan/23', $this->collection));
        $this->assertInstanceOf('Dobee\\Routing\\Route', RouteMatcher::match('/test', $this->collection));
    }

    public function testMatchRouteParameters()
    {
        try {
            $route = RouteMatcher::match('/test', $this->collection);
        } catch (RouteException $e) {
            return ;
        }

        $this->assertInstanceOf('Dobee\\Routing\\Route', $route);

        $this->assertEquals(array('name' => 'jan', 'age' => 22), $route->getParameters());

        try {
            $route = RouteMatcher::match('/test/janhuang', $this->collection);
        } catch (RouteException $e) {
            return ;
        }

        $this->assertEquals(array('name' => 'janhuang', 'age' => 22), $route->getParameters());

        try {
            $route = RouteMatcher::match('/test/janhuang/23', $this->collection);
        } catch (RouteException $e) {
            return ;
        }

        $this->assertEquals(array('name' => 'janhuang', 'age' => 23), $route->getParameters());
    }

    public function testRouteFormat()
    {
        try {
            $route = RouteMatcher::match('/method', $this->collection);
        } catch (RouteException $e) {
            return true;
        }

        $this->assertTrue(RouteMatcher::matchRequestFormat('php', $route));
        $this->assertTrue(RouteMatcher::matchRequestFormat('json', $route));
        $this->assertTrue(RouteMatcher::matchRequestFormat('xml', $route));

        try {
            $this->assertTrue(RouteMatcher::matchRequestFormat('html', $route));
        } catch (RouteException $e) {
            return true;
        }

        try {
            $this->assertTrue(RouteMatcher::matchRequestFormat('html5', $route));
        } catch (RouteException $e) {
            return true;
        }
    }

    public function testRouteRequestMethods()
    {
        try {
            $route = RouteMatcher::match('/method', $this->collection);
        } catch (RouteException $e) {
            return true;
        }

        try {
            $this->assertTrue(RouteMatcher::matchRequestMethod('ANY', $route));
        } catch (RouteException $e) {
            return true;
        }

        try {
            $this->assertTrue(RouteMatcher::matchRequestMethod('GET', $route));
        } catch (\Exception $e) {
            return true;
        }

        try {
            $this->assertTrue(RouteMatcher::matchRequestMethod('POST', $route));
        } catch (\Exception $e) {
            return true;
        }

        try {
            $this->assertTrue('Dobee\\Routing\\RouteException', RouteMatcher::matchRequestMethod('PUT', $route));
        } catch (\Exception $e) {

        }

    }
}
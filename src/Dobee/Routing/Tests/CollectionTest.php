<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/31
 * Time: 下午11:17
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Route;
use Dobee\Routing\RouteCollections;
use Dobee\Routing\RouteException;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public $collection;

    public function setUp()
    {
        $this->collection = new RouteCollections();

        $this->collection->setRoute(new Route('/demo/{name}', 'demo'));
        $this->collection->setRoute(new Route('/test/{name}', 'test'));
    }

    public function testRouteIsHad()
    {
        $this->assertNotFalse($this->collection->hasRoute('demo'));
        $this->assertNotTrue($this->collection->hasRoute('ad'));
    }

    public function testRouteExceptionOne()
    {
        try {
            $this->collection->getRoute('abc');
        } catch (RouteException $exception) {
            return ;
        }

        $this->fail('not exception');
    }

    public function testRouteExceptionTwo()
    {
        $this->setExpectedException(
            'Dobee\\Routing\\RouteException', sprintf('Route "%s" is not found.', 'abc'), 0
        );

        $this->collection->getRoute('abc');
    }

    public function testRoute()
    {
        $this->assertInstanceOf('Dobee\\Routing\\Route', $this->collection->getRoute('demo'));
    }

    public function testRouteName()
    {
        $this->assertEquals('demo', $this->collection->getRoute('demo')->getName());
    }

    public function testRouteRegex()
    {
        $this->assertEquals('/^\/demo\/{1}(?P<name>.+)$/', $this->collection->getRoute('demo')->getPathRegex());
    }
}
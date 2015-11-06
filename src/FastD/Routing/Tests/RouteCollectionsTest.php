<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 下午10:56
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Route;
use FastD\Routing\RouteCollections;

class RouteCollectionsTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRoute()
    {
        $collection = new RouteCollections();
        $demoRoute = new Route('/demo', 'demo', function () {});
        $collection->setRoute($demoRoute);
        foreach ($collection as $route) {
            $this->assertEquals('/demo', $route->getPath());
        }
    }
}

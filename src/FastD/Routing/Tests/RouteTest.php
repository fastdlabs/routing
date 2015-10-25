<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 上午10:53
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRouteBaseProperty()
    {
        $baseRoute = new Route('/', 'root');
        $this->assertEquals($baseRoute->getParameters(), []);
        $this->assertEquals($baseRoute->getCallback(), null);
        $this->assertEquals($baseRoute->getDefaults(), []);
        $this->assertEquals($baseRoute->getDomain(), '');
        $this->assertEquals($baseRoute->getFormats(), ['php']);
        $this->assertEquals($baseRoute->getGroup(), '');
        $this->assertEquals($baseRoute->getIps(), []);
        $this->assertEquals($baseRoute->getMethods(), ['ANY']);
        $this->assertEquals($baseRoute->getName(), 'root');
        $this->assertEquals($baseRoute->getPath(), '/');
        $this->assertEquals($baseRoute->getRequirements(), []);
        $this->assertEquals($baseRoute->getPathRegex(), '/^\/$/');
        $this->assertEquals($baseRoute->getSchema(), 'http');
    }

    public function testRouteArgument()
    {
        $argRoute = new Route('/{id}', 'article');
        $this->assertEquals($argRoute->getParameters(), ['id']);
        $this->assertEquals($argRoute->getDefaults(), []);
        $this->assertEquals($argRoute->getRequirements(), []);
        $this->assertEquals($argRoute->getPathRegex(), '/^\/{1}(?P<id>.+)$/');

        $argRoute = new Route('/{id}', 'article', ['id' => 1]);
        $this->assertEquals($argRoute->getParameters(), ['id']);
        $this->assertEquals($argRoute->getDefaults(), ['id' => 1]);
        $this->assertEquals($argRoute->getRequirements(), []);
        $this->assertEquals($argRoute->getPathRegex(), '/^\/{1}(?P<id>.+)$/');

        $argRoute = new Route('/{id}', 'article', ['id' => 1], ['ANY'], ['id' => '\d+']);
        $this->assertEquals($argRoute->getParameters(), ['id']);
        $this->assertEquals($argRoute->getDefaults(), ['id' => 1]);
        $this->assertEquals($argRoute->getRequirements(), ['id' => '\d+']);
        $this->assertEquals($argRoute->getPathRegex(), '/^\/{1}(?P<id>\d+)$/');
    }
}

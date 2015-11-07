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
use FastD\Routing\RouteGroup;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRouteBaseProperty()
    {
        $baseRoute = new Route('/', 'root', function () {return 'hello world';});
        $this->assertEquals($baseRoute->getParameters(), []);
        $this->assertEquals($baseRoute->getDefaults(), []);
        $this->assertEquals($baseRoute->getDomain(), '');
        $this->assertEquals($baseRoute->getFormats(), ['php']);
        $this->assertEquals($baseRoute->getGroup(), '');
        $this->assertEquals($baseRoute->getIps(), []);
        $this->assertEquals($baseRoute->getMethods(), ['ANY']);
        $this->assertEquals($baseRoute->getName(), 'root');
        $this->assertEquals($baseRoute->getPath(), '/');
        $this->assertEquals($baseRoute->getRequirements(), []);
        $this->assertEquals($baseRoute->getPathRegex(), '/^\/{0,1}$/');
        $this->assertEquals($baseRoute->getSchema(), 'http');
    }

    public function testRouteArgument()
    {
        $argRoute = new Route('/{id}', 'article');
        $this->assertEquals($argRoute->getDefaults(), []);
        $this->assertEquals($argRoute->getRequirements(), []);
        $this->assertEquals($argRoute->getPathRegex(), '/^\/{1}(?P<id>.+)$/');
        $this->assertEquals($argRoute->getParameters(), ['id']);

        $argRoute = new Route('/{id}', 'article', null, ['GET'], ['id' => 1]);
        $this->assertEquals($argRoute->getDefaults(), ['id' => 1]);
        $this->assertEquals($argRoute->getRequirements(), []);
        $this->assertEquals($argRoute->getPathRegex(), '/^\/{1}(?P<id>.+)$/');
        $this->assertEquals($argRoute->getParameters(), ['id']);

        $argRoute = new Route('/{id}', 'article', null, ['ANY'], ['id' => 1], ['id' => '\d+']);
        $this->assertEquals($argRoute->getDefaults(), ['id' => 1]);
        $this->assertEquals($argRoute->getRequirements(), ['id' => '\d+']);
        $this->assertEquals($argRoute->getPathRegex(), '/^\/{1}(?P<id>\d+)$/');
        $this->assertEquals($argRoute->getParameters(), ['id']);
    }

    public function testRouteGroup()
    {

    }
}

<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 下午11:24
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

class RouteGroupTest extends \PHPUnit_Framework_TestCase
{
    public function testGroup()
    {
        $group = new RouteGroup('/root', 'root_group', function (RouteGroup $routeGroup) {});
        $group->setDomain('www.baidu.com');
        $group->setSchema('https');
        $this->assertEquals('/root', $group->getPath());
        $this->assertEquals('root_group', $group->getName());
        $this->assertEquals('https', $group->getSchema());
        $this->assertEquals('www.baidu.com', $group->getDomain());
    }

}

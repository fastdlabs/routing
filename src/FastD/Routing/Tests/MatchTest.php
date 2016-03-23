<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 下午6:43
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Matcher\RouteMatcher;
use FastD\Routing\Route;

class MatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route = new Route('/name/{name}', '3', ['name' => 'jan']);
        $this->assertRegExp($route->getPathRegex(), '/name/jan');
        $this->assertEquals(['name' => 'jan'], $route->getParameters());


        $route = new Route('/name/{name}/{age}', '3', ['age' => 12]);
        $this->assertRegExp($route->getPathRegex(), '/name/jan/13');
        RouteMatcher::matchRoute('/name/jan', $route);
        $this->assertEquals($route->getParameters(), ['name' => 'jan', 'age' => 12]);
        RouteMatcher::matchRoute('/name/jan/13', $route);
        $this->assertEquals($route->getParameters(), ['name' => 'jan', 'age' => 13]);
    }
}
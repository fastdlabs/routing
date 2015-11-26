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

use FastD\Routing\Route;

class MatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route1 = new Route('/', '1');

        $route2 = new Route('/name', '2');
        $route3 = new Route('/name/{name}', '3');

        foreach ([$route1, $route2, $route3] as $route) {
            if ($route->match('/')) {
                $this->assertEquals('/', $route->getPath());
            }
        }
    }
}
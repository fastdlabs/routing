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


        $route3 = new Route('/name/{name}', '3', ['name' => 'jan']);
        $this->assertRegExp($route3->getPathRegex(), '/name/jan');
        $this->assertTrue($route3->match('/name'));
        $this->assertEquals(['name' => 'jan'], $route3->getParameters());
        $this->assertTrue($route3->match('/name/janhuang'));
        $this->assertEquals(['name' => 'janhuang'], $route3->getParameters());


        $route4 = new Route('/name/{name}/{age}', '3', ['age' => 12]);
        $this->assertRegExp($route4->getPathRegex(), '/name/jan/13');
        $this->assertFalse($route4->match('/name'));
        $this->assertTrue($route4->match('/name/janhuang/13'));
        $this->assertEquals(['name' => 'janhuang', 'age' => 13], $route4->getParameters());
        $this->assertTrue($route4->match('/name/janhuang'));
        $this->assertEquals(['name' => 'janhuang', 'age' => 12], $route4->getParameters());
    }
}
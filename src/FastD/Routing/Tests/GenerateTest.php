<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 下午9:35
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Route;
use FastD\Routing\RouteGenerator;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $route4 = new Route('/name/{name}', '3', ['name' => 'jan']);
        $route4->setFormats(['json']);
        $this->assertEquals('/name/jan', RouteGenerator::generateUrl($route4));
        $this->assertEquals('/name/janhuang', RouteGenerator::generateUrl($route4, ['name' => 'janhuang']));
        $this->assertEquals('/name/janhuang.json', RouteGenerator::generateUrl($route4, ['name' => 'janhuang'], 'json'));
        $this->assertEquals('/name/janhuang', RouteGenerator::generateUrl($route4, ['name' => 'janhuang'], 'jsp'));

        $route3 = new Route('/name', '3');
        $route3->setFormats(['json']);
        $this->assertEquals('/name', RouteGenerator::generateUrl($route3));
    }

    public function testGenerateDefaultArgs()
    {
        $route = new Route('/{name}', function () {}, ['name' => 'janhuang']);
        $this->assertEquals('/janhuang', RouteGenerator::generateUrl($route));
        $route = new Route('/{name}', function () {}, ['name' => '']);
        $this->assertEquals('/', RouteGenerator::generateUrl($route));
        $route = new Route('/name/{name}', function () {}, ['name' => '']);
        $this->assertEquals('/name/', RouteGenerator::generateUrl($route));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午9:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Route;
use Dobee\Routing\RouteException;
use Dobee\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    public $router;

    public function setUp()
    {
        $this->router = new Router();

        $this->router->setRoute(new Route('/demo/{name}', 'demo'));
        $this->router->setRoute(new Route('/test/{name}', 'test'));
    }

    public function testHasRoute()
    {
        $this->assertFalse($this->router->hasRoute('abc'));
        $this->assertTrue($this->router->hasRoute('demo'));
    }

    public function testGetRoute()
    {
        $this->assertEquals('/^\/demo\/{1}(?P<name>.+)$/', $this->router->getRoute('demo')->getPathRegex());

        $this->assertEquals(array('php'), $this->router->getRoute('demo')->getFormat());
    }

    public function testMatch()
    {
        try {
            $route = $this->router->match('/demo/jan');
        } catch (RouteException $e) {
            return true;
        }

        $this->assertEquals('demo', $route->getName());

        try {
            $route = $this->router->match('/test/jan');
        } catch (RouteException $e) {
            return true;
        }

        $this->assertEquals('test', $route->getName());
    }

    public function testGenerator()
    {
        $this->assertEquals('/demo/jan.php', $this->router->generateUrl('demo', array('jan')));
        $this->assertEquals('/test/jan.php', $this->router->generateUrl('test', array('jan')));
    }
}
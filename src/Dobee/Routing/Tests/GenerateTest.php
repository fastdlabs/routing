<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/23
 * Time: 下午11:51
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Route;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Dobee\Routing\Router
     */
    private $router;

    /**
     * @var \Dobee\Routing\Router
     */
    private $clone;

    /**
     * @var \Dobee\Routing\Route[]
     */
    private $routes;

    public function setUp()
    {
        if (!class_exists('\Routes')) {
            include __DIR__ . '/../Routes.php';
        }

        $this->router = \Routes::getRouter();

        $this->clone = clone $this->router;

        $this->routes['welcome']    = new Route('/welcome', 'welcome'); // 普通静态路由
        $this->routes['arguments']  = new Route('/arguments/{arg1}/{arg2}', 'arguments'); // 无默认值动态路由
        $this->routes['once']       = new Route('/once/{name}', 'once', array('name' => 'jan'));  // 有默认值动态路由
        $this->routes['host']       = new Route('/host', 'host');
        $this->routes['host']->setDomain('localhost');

        foreach ($this->routes as $name => $route) {
            $this->router->setRoute($route);
        }
    }

    public function testClone()
    {
        $this->assertEquals($this->router, $this->clone);
    }

    public function testStatic()
    {
        $this->assertEquals('/welcome', $this->router->generateUrl('welcome'));
    }

    public function testDynamic()
    {
        $this->assertEquals('/once/jan', $this->router->generateUrl('once'));
        $this->assertEquals('/once/janhuang', $this->router->generateUrl('once', array('name' => 'janhuang')));
        $this->assertEquals('/arguments/jan/23', $this->router->generateUrl('arguments', array('arg1' => 'jan', 'arg2' => 23)));
    }

    public function testBindHost()
    {
        $this->assertEquals('http://localhost/host', $this->router->generateUrl('host'));
    }
}
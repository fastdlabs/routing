<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/22
 * Time: 下午11:42
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Route;
use Dobee\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Dobee\Routing\Router
     */
    private $router;

    /**
     * @var Route[]
     */
    private $routes = array();

    public function setUp()
    {
        $this->router = new Router();

        $this->routes['welcome']    = new Route('/welcome', 'welcome'); // 普通静态路由
        $this->routes['arguments']  = new Route('/arguments/{arg1}/{arg2}', 'arguments'); // 无默认值动态路由
        $this->routes['once']       = new Route('/once/{name}', 'once', array('name' => 'jan'));  // 有默认值动态路由
        $this->routes['method']     = new Route('/welcome/post', 'welcome_post', array(), array('POST')); // 带访问方法限制路由
        $this->routes['requires']   = new Route('/welcome/{require}', 'welcome_post', array(), array('GET'), array('require' => '\d{1}')); // 带访问参数格式限制路由
        $this->routes['requires2']  = new Route('/welcome/{require2}', 'welcome_post', array(), array('GET'), array('require2' => '\d+')); // 带访问参数格式限制路由
        $this->routes['requires3']  = new Route('/welcome/{require3}', 'welcome_post', array(), array('GET'), array('require3' => '\w+')); // 带访问参数格式限制路由
        $this->routes['format']     = new Route('/format', 'format', array(), array(), array(), array('json', 'php', 'xml')); // 普通静态路由
        $this->routes['host']       = (new Route('/host', 'host'))->setHost('localhost');

        foreach ($this->routes as $route) {
            $this->router->setRoute($route);
        }
    }

    public function testDynamic()
    {
        $route = $this->router->matchRoute('/arguments/1/1', $this->routes['arguments']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array('arg1', 'arg2'), $route->getArguments());
        $this->assertEquals(array('arg1' => 1, 'arg2' => 1), $route->getParameters());

        $route = $this->router->matchRoute('/once/janhuang', $this->routes['once']);
        $this->assertEquals(array('name' => 'jan'), $route->getDefaults());
        $this->assertEquals(array('name'), $route->getArguments());
        $this->assertEquals(array('name' => 'janhuang'), $route->getParameters());

        $route = $this->router->matchRoute('/once', $this->routes['once']);
        $this->assertEquals(array('name' => 'jan'), $route->getDefaults());
        $this->assertEquals(array('name'), $route->getArguments());
        $this->assertEquals(array('name' => 'jan'), $route->getParameters());
    }

    public function testStatic()
    {
        $route = $this->router->matchRoute('/welcome', $this->routes['welcome']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array(), $route->getArguments());
        $this->assertEquals(array(), $route->getParameters());
    }

    public function testMethods()
    {
        $route = $this->router->matchRoute('/welcome/post', $this->routes['method']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array(), $route->getArguments());
        $this->assertEquals(array(), $route->getParameters());
        $this->assertEquals(array('POST'), $route->getMethods());
    }

    public function testRequirements()
    {
        $route = $this->router->matchRoute('/welcome/1', $this->routes['requires']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array('require'), $route->getArguments());
        $this->assertEquals(array('require' => null), $route->getParameters());
        $this->assertEquals(array('GET'), $route->getMethods());
        $this->assertEquals(array('require' => '\d{1}'), $route->getRequirements());

        $route = $this->router->matchRoute('/welcome/1', $this->routes['requires2']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array('require2'), $route->getArguments());
        $this->assertEquals(array('require2' => null), $route->getParameters());
        $this->assertEquals(array('GET'), $route->getMethods());
        $this->assertEquals(array('require2' => '\d+'), $route->getRequirements());

        $route = $this->router->matchRoute('/welcome/1', $this->routes['requires3']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array('require3'), $route->getArguments());
        $this->assertEquals(array('require3' => null), $route->getParameters());
        $this->assertEquals(array('GET'), $route->getMethods());
        $this->assertEquals(array('require3' => '\w+'), $route->getRequirements());
    }

    public function testFormats()
    {
        $route = $this->router->matchRoute('/format', $this->routes['format']);
        $this->assertEquals(array(), $route->getDefaults());
        $this->assertEquals(array(), $route->getArguments());
        $this->assertEquals(array(), $route->getParameters());
        $this->assertEquals(array('ANY'), $route->getMethods());
        $this->assertEquals(array(), $route->getRequirements());
        $this->assertEquals(array('json', 'php', 'xml'), $route->getFormats());
    }

    public function testHost()
    {
        $route = $this->routes['host'];

        $this->assertTrue($this->router->matchHost('localhost', $route));
    }
}
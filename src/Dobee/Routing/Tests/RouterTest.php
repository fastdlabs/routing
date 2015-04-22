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

        $this->routes['welcome'] = new Route('/welcome', 'welcome');
        $this->routes['hello'] = new Route('/hello/{name}', 'hello', array(
            'name' => 'jan'
        ));

        foreach ($this->routes as $route) {
            $this->router->setRoute($route);
        }
    }

    public function testDynamic()
    {
        $route = $this->router->matchRoute('/hello', $this->routes['hello']);

        $route = $this->router->matchRoute('/hello/janhuang', $this->routes['hello']);
    }

//    public function testStatic()
//    {
//        $route = $this->router->matchRoute('/welcome', $this->routes['welcome']);
//    }
}
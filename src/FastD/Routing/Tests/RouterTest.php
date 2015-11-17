<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/10
 * Time: 上午12:51
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    protected $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testRouterCreate()
    {
        $route = $this->router->get('/', null);
        $route->setName('root');
        $this->assertEquals('root', $route->getName());
    }

}

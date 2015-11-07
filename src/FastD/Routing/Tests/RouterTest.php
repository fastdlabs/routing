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

    public function testRoute()
    {
        $getRoute = $this->router->get('/', 'root_get', function () {});
        $this->assertEquals($getRoute->getMethods(), ['GET']);
        $this->assertEquals('root_get', $getRoute->getName());
        $this->assertEquals('/', $getRoute->getPath());
        $this->assertEquals('/^\/{0,1}$/', $getRoute->getPathRegex());
        $this->assertRegExp($getRoute->getPathRegex(), '/');
        $this->assertRegExp($getRoute->getPathRegex(), '');

        $postRoute = $this->router->post('/post', 'root_post', function () {});
        $this->assertEquals(['POST'], $postRoute->getMethods());
        $this->assertEquals('/^\/post$/', $postRoute->getPathRegex());
    }

    public function testRouteMatchMethods()
    {
        $route1 = $this->router->match(['POST', 'GET'], '/', 'root_path', function () {});
        $this->assertEquals(['POST', 'GET'], $route1->getMethods());
    }

    public function testRouteWith()
    {
        $group = $this->router->with('/root', 'root_with', function (Router $router) {
            $router->get('/', 'root_with_get', function () {});
            $router->with('/v1', 'root_v1', function () {

            });
        });

        $route = $group->getRoute('root_with_get');

        $this->assertEquals('root_with_get', $route->getName());
        $this->assertEquals('/root/', $route->getPath());
    }
}

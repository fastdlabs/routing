<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/23
 * Time: 下午12:20
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace FastD\Routing\Tests;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FastD\Routing\Router
     */
    private $router;

    public function setUp()
    {
        $this->router = \Routes::getRouter();
    }

    public function testSetRoutes()
    {
        /**
         * get
         */
        \Routes::get(['/welcome', 'name' => 'welcome'], function () {
            return 'welcome';
        });

        $route = $this->router->matchRoute('/welcome', $this->router->getRoute('welcome'));

        $callback = $route->getCallback();

        $this->assertEquals('welcome', $callback());

        /**
         * post
         */
        \Routes::post(['/post', 'name' => 'post'], function () {
            return 'post';
        });

        $route = $this->router->matchRoute('/post', $this->router->getRoute('post'));

        $this->assertEquals(array('POST'), $route->getMethods());
    }

    public function testDynamic()
    {
        \Routes::any(['/one/{name}', 'name' => 'one'], function ($name) {
            return $name;
        })->setDefaults(array('name' => 'janHuang'));

        $route = $this->router->getRoute('one');
        $route = $this->router->matchRoute('/one/jan', $route);

        $this->assertEquals(array('name' => 'janHuang'), $route->getDefaults());
        $this->assertEquals(array('name'), $route->getArguments());
        $this->assertEquals(array('name' => 'jan'), $route->getParameters());
    }

    public function testMethods()
    {
        \Routes::getRouter()->createRoute(['/method', 'name' => 'method'], function () {}, array('POST',"GET"));

        $this->assertEquals(array('POST', 'GET'), $this->router->getRoute('method')->getMethods());
    }

    public function testGroup()
    {
        \Routes::group('/admin', function () {
           \Routes::get(['/login', 'name' => 'admin_login'], '');
        });

        $route = $this->router->getRoute('admin_login');

        $this->assertEquals('/admin/login', $route->getPath());
        $this->assertEquals('/admin', $route->getGroup());

        \Routes::group(['/api', 'domain' => 'api.demo.com'], function () {
            \Routes::group('/v1', function () {
                \Routes::get(['/demo', 'name' => 'api_v1_domain'], function () {
                    return 'api demo';
                });
            });
        });

        $route = \Routes::getRouter()->getRoute('api_v1_domain');
        $this->assertEquals('http://api.demo.com/v1/demo', \Routes::getRouter()->generateUrl('api_v1_domain'));
        $this->assertEquals('/v1/demo', $route->getPath());
    }
}
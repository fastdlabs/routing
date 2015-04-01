<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/31
 * Time: 下午9:32
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Route
     */
    protected $route;

    public function setUp()
    {
        $this->route = new Route(
            '/{name}',
            'demo',
            array(
                'name' => 'jan'
            ),
            array(
                'POST',
                'GET'
            ),

            array()
        );
    }

    public function testRouteRegex()
    {
        $this->assertEquals($this->route->getPathRegex(), '/^\/{1}(?P<name>.+)$/');
    }

    public function testRouteMethods()
    {
        $this->assertEquals($this->route->getMethod(), array('POST', 'GET'));
    }

    public function testRouteArguments()
    {
        $this->assertEquals($this->route->getArguments(), array('name'));
    }

    public function testRouteDefaults()
    {
        $this->assertEquals($this->route->getDefaults(), array(
            'name' => 'jan'
        ));
    }

    public function testRouteFormat()
    {
        $this->assertEquals(array('php'), $this->route->getFormat());
    }

    public function testRouteRequirements()
    {
        $this->assertEquals(array(), $this->route->getRequirements());
    }
}
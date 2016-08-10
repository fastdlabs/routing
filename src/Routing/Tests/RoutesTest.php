<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/18
 * Time: 下午8:53
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing\Tests;

use FastD\Routing\Router;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    protected $router;

    public function setUp()
    {
        include_once __DIR__ . '/../Routes.php';

        $this->router = \Routes::getRouter();
    }

    public function testAddRoute()
    {
        \Routes::get('test', '/', function () {});

        $this->assertEquals([
            'test' => '/:get'
        ], \Routes::getRouter()->getMap());
    }
}
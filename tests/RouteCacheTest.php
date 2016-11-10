<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;

use FastD\Routing\RouteCollection;

class RouteCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteCollection
     */
    protected $collection;

    protected $cache;

    public function setUp()
    {
        $collection = new RouteCollection();

        if (!$collection->cache->hasCache()) {
            $collection->addRoute('GET', '/test', []);
            $collection->addRoute('GET', '/test/{name}', []);
            $collection->addRoute('GET', '/user/profile/{name}', []);
            $collection->addRoute('POST', '/user/profile/{name}', []);
            $collection->addRoute('ANY', '/user/email/{name}', []);
        } else {
            $collection->loadCaching(__DIR__);
        }

        $this->collection = $collection;
    }

    public function testCache()
    {
        $route = $this->collection->match('GET', '/test/123');

        $this->assertEquals([
            'name' => '123',
        ], $route->getParameters());
    }
}

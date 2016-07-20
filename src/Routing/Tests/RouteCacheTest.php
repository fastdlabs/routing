<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;

use FastD\Routing\RouteCache;

class RouteCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testToCache()
    {
        $cache = new RouteCache();

        $cache->addRoute('test', 'GET', '/', function () {});

        echo $cache->toCache();
    }
}

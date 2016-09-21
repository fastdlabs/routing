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
use FastD\Routing\RouteCollection;

class RouteCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testToCache()
    {
        $collections = new RouteCollection();

        $collections->addRoute('test', 'GET', '/', function () {});
        $cache = new RouteCache($collections, __DIR__);

        unset($cache);


    }
}

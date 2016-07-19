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

use FastD\Routing\RouteRegex;

class RouteRegexTest extends \PHPUnit_Framework_TestCase
{
    public function testRegex()
    {
        $routeRegex = new RouteRegex();

        $regex = $routeRegex->parse('/test/{name:\d+}/[{age}]');

        echo $regex;

        $this->assertRegExp('~^(' . $regex . ')$~', '/test/18');
    }
}

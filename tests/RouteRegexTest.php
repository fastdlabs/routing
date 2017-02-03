<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Routing\RouteRegex;

class RouteRegexTest extends PHPUnit_Framework_TestCase
{
    public function testRegex()
    {
        $regex = new RouteRegex('/test/{name:\d+}/[{age}]');
        $this->assertRegExp('~^(' . $regex->getRegex() . ')$~', '/test/18');
        $this->assertEquals(['name', 'age'], $regex->getVariables());
        $this->assertEquals([
            'name' => '\d+',
            'age' => '[^/]+'
        ], $regex->getRequirements());
    }

    public function testMatchingLastCharset()
    {
        $regex = new RouteRegex('/{name}/');

        $this->assertRegExp('~^' . $regex->getRegex() . '$~', '/foo');
        $this->assertRegExp('~^' . $regex->getRegex() . '$~', '/foo/');
    }

    public function testFuzzyMatchingRoute()
    {
        $regex = new RouteRegex('/*');

        $this->assertRegExp('~^' . $regex->getRegex() . '$~', '/test/18');

        $regex = new RouteRegex('/abc/*');

        $this->assertRegExp('~^' . $regex->getRegex() . '$~', '/abc/foo/bar');

        $regex = new RouteRegex('/foo/*');

        $this->assertRegExp('~^' . $regex->getRegex() . '$~', '/foo/foo/bar');
    }
}

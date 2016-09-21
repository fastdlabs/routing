<?php
/**
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
        $regex = new RouteRegex('/test/{name:\d+}/[{age}]');

        $this->assertRegExp('~^(' . $regex->getRegex() . ')$~', '/test/18');

        $this->assertEquals(['name', 'age'], $regex->getVariables());

        $this->assertEquals([
            'name' => '\d+',
            'age' => '[^/]+'
        ], $regex->getRequirements());
    }
}

<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Routing\RouteParser;
use PHPUnit\Framework\TestCase;

class RouteParserTest extends TestCase
{
    public function testParse()
    {
        $parser = new RouteParser();

        $parsed = $parser->parse("/{name}/{age}");

        $this->assertEquals([
            [
                '/',
                [
                    'name',
                    '[^/]+'
                ],
                '/',
                [
                    'age',
                    '[^/]+'
                ]
            ]
        ], $parsed);
    }
}

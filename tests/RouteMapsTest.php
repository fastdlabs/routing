<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Routing\RouteMaps;
use FastD\Routing\RouteParser;
use PHPUnit\Framework\TestCase;

class RouteMapsTest extends TestCase
{
    public function testAddRoute()
    {
        $mapper = new RouteMaps();
        $parser = new RouteParser();

        $routeDatas = $parser->parse("/{name}/{age}");

        foreach (["GET"] as $value) {
            foreach ($routeDatas as $routeData) {
                $mapper->addRoute($value, $routeData, '', [], []);
            }
        }
    }
}

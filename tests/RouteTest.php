<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testBaseRoute()
    {
        $route = new Route('GET', '/');
        $this->assertEmpty($route->getName());
        $route->setName("root");
        $this->assertEquals("root", $route->getName());
    }

    public function testDynamicRoute()
    {
        $route = new Route('GET', '/{name}');
        $this->assertFalse($route->isStatic());
    }

    public function testHandle()
    {
        include __DIR__ . '/handle/hello.php';
        $route = new Route('GET', '/');
        $route->handle(hello::class);
        print_r($route);
    }
}

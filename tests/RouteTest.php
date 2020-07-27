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
        $this->assertEquals($route->getMethod(), 'GET');
    }

    public function testDynamicRoute()
    {
        $route = new Route('GET', '/{name}');
        $this->assertFalse($route->isStatic());
    }

    public function testHandle()
    {
        include_once __DIR__ . '/handle/hello.php';
        $route = new Route('GET', '/');
        $route->handle(hello::class);
        $this->assertInstanceOf(\FastD\Routing\Handle\RouteHandleInterface::class, $route->getHandle());
    }
}

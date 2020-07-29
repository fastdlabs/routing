<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use PHPUnit\Framework\TestCase;

class RouteDispatcherTest extends TestCase
{
    public function testDispatch()
    {
        $collections = new RouteCollection();
        $d = new RouteDispatcher($collections);
        $collections->addRoute("GET", "/", "");
        $response = $d->dispatch(new ServerRequest("GET", '/'));
        print_r($response);
    }
}

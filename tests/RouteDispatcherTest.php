<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Tests;



use FastD\Http\ServerRequest;
use FastD\Middleware\Dispatcher;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteMiddleware;

class RouteDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatcher()
    {
        $routeCollection = new RouteCollection();

        $routeCollection->get('/', function () {
            return 'hello world';
        });

        $dispatcher = new Dispatcher([
            new RouteMiddleware($routeCollection)
        ]);

        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $response->getBody()->rewind();
        print_r($response->getBody()->getContents());
    }
}

<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

class RouteDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/middleware/AfterMiddleware.php';
        include_once __DIR__ . '/middleware/BeforeMiddleware.php';
    }

    public function testDispatcher()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->get('/', function () {
            echo 'hello world';
        });
        $dispatcher = new RouteDispatcher($routeCollection, [
            new AfterMiddleware(),
            new BeforeMiddleware(),
        ]);
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
        echo $response->getBody();
    }
}

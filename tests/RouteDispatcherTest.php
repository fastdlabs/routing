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
        $collection = new RouteCollection();
        $collection
            ->prefix('/api')
            ->group(function (RouteCollection $routeCollection) {
                $routeCollection->get('/', function () {
                    return new \FastD\Http\Response();
                });
            })
        ;

        $request = new ServerRequest('GET', '/api');

        $dispatcher = new RouteDispatcher($collection);

        try {
            $response = $dispatcher->dispatch($request);
        } catch (Exception $e) {
        }

        $this->assertEmpty($response->getBody()->getContents());
    }
}

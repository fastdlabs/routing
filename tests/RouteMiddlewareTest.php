<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;
use FastD\Routing\Route;
use FastD\Routing\RouteMiddleware;

class RouteMiddlewareTest extends PHPUnit_Framework_TestCase
{
    protected $response;

    protected function response()
    {
        if (null === $this->response) {
            $this->response = new Response();
        }

        return $this->response;
    }

    public function testRouteMiddleware()
    {
        $middleware = new RouteMiddleware(new Route('GET', '/', function (ServerRequest $request) {
            return $this->response()->withContent('hello');
        }));

        $response = $middleware->handle(new ServerRequest('GET', '/'), new Delegate(function () {

        }));

        echo $response->getBody();
        $this->expectOutputString('hello');
    }
}

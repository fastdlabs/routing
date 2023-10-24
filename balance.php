<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Middleware\Middleware;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

include __DIR__ . '/vendor/autoload.php';

$request = new ServerRequest('GET', '/g/30000');

class DemoHandler extends Middleware
{
    public function process(ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response('hello');
    }
}

for ($n = 0; $n < 100; $n++) {
    $nRoutes = 10000;
    $nMatches = 300;

    $routeCollection = new RouteCollection();

    $startTime = microtime(true);
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $routeCollection->addRoute('GET', '/' . $str . '/{arg}', DemoHandler::class);
    }
    $router = new RouteDispatcher($routeCollection);
    for ($i = 0; $i < $nMatches; $i++) {
        $res = $router->dispatch($request);
    }
    printf("FastD first route: %f\n", microtime(true) - $startTime);
}


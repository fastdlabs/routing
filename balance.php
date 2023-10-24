<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

include __DIR__ . '/vendor/autoload.php';

$request = new \FastD\Http\ServerRequest('GET', '/g/30000');

class DemoHandler extends \FastD\Middleware\Middleware
{
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        return new \FastD\Http\Response('hello');
    }
}

for ($n = 0; $n < 100; $n++) {
    $nRoutes = 10000;
    $nMatches = 300;

    $routeCollection = new \FastD\Routing\RouteCollection();

    $startTime = microtime(true);
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $routeCollection->addRoute('GET', '/' . $str . '/{arg}', DemoHandler::class);
    }
    $router = new \FastD\Routing\RouteDispatcher($routeCollection);
    for ($i = 0; $i < $nMatches; $i++) {
        $res = $router->dispatch($request);
    }
    printf("FastD first route: %f\n", microtime(true) - $startTime);
}


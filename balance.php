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

for ($n = 0; $n < 100; $n++) {
    $nRoutes = 1000;
    $nMatches = 300;

    $router = new \FastD\Routing\RouteCollection();

    $startTime = microtime(true);
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/{arg}', function () {
            return 'hello world';
        });
    }
    for ($i = 0; $i < $nMatches; $i++) {
        $res = $router->match($request);
    }
    printf("FastD first route: %f\n", microtime(true) - $startTime);
}


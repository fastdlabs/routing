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

for ($n = 0; $n < 10; $n++) {
    $nRoutes = 100;
    $nMatches = 3000;

    $router = new \FastD\Routing\RouteCollection();

    $startTime = microtime(true);
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/{arg}', function () {
            return 'hello world';
        }, 'branch' . $i);
    }
    for ($i = 0; $i < $nMatches; $i++) {
        $res = $router->dispatch('GET', '/g/30000');
    }
    printf("FastD first route: %f\n", microtime(true) - $startTime);
}


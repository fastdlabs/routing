<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

include __DIR__ . '/../vendor/autoload.php';

$nRoutes = 10000;
$nMatches = 30000;

$router = new \FastD\Routing\Router();

$startTime = microtime(true);
for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
    $router->addRoute('GET', '/' . $str . '/{arg}', function () {
        return 'hello world';
    });
}
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/a/30000');
}
printf("FastD first route: %f\n", microtime(true) - $startTime);
die;
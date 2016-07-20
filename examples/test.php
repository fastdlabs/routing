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

echo '<pre>';

$router = new \FastD\Routing\RouteCollection();

for ($i = 0; $i < 15; $i++) {
    $router->addRoute($i, 'GET', '/{test}/test' . $i . '', function () use ($i) {
        return 'hello POST ' . $i;
    });
}

print_r($router->dispatch('GET', '/janhuang/test1', []));
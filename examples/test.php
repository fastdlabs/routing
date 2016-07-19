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

$router = new \FastD\Routing\Router();

$router->addRoute('GET', '/test', function () {
    return 'hello GET';
});

$router->addRoute('POST', '/test', function () {
    return 'hello POST';
});

$router->addRoute('POST', '/{user}/profile', function () {
    return 'hello POST';
});

$router->addRoute('GET', '/{user}/profile', function () {
    return 'hello POST';
});
$router->addRoute('GET', '/{test}/test', function () {
    return 'hello POST';
});
echo '<pre>';
print_r($router->getDynamicsMap());
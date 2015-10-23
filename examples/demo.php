<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/11
 * Time: 下午10:44
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

$router = new \FastD\Routing\Router();

$router->createRoute('/', function () {
    echo 'hello world';
});

$route = $router->match('/');

echo '<pre>';
print_r($route);
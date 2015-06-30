<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/30
 * Time: 下午12:31
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

$router->createRoute(['/{name}', 'name' => 'jan'], function () {
    echo 'hello jan';
});

$route = $router->match('/');
echo '<pre>';
print_r($route);
$callback = $route->getCallback();
$callback();
echo '<br />';
echo $router->generateUrl('/', ['name' => 'janhuang']);
echo '<br />';
echo $router->generateUrl('jan', ['name' => 'janhuang', 'age' => 12]);


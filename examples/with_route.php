<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/7
 * Time: 下午5:17
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

$router = new \FastD\Routing\Router();

$router->with('/root', function (\FastD\Routing\Router $router) {
    $router->name('root_index')->get('/index', function () {});
    $router->with('/demo', function (\FastD\Routing\Router $router) {
        $router->name('root_demo_index')->get('/index', function () {});
    });
});

echo '<pre>';

print_r($router);







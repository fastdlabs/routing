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

$with = $router->with('/demo', 'demo_with', function (\FastD\Routing\Router $router) {

    $router->post('/post', 'demo_post', function () {});

    $router->with('/v3', 'demo_v3', function (\FastD\Routing\Router $router) {
        $router->put('/put', 'demo_v3_put', function () {});
    });
});

$router->get('/', 'demo_get', function () {
    return 'hello world';
});

echo '<pre>';

//print_r($with);
$response = $router->dispatch('demo_get');

echo $response();








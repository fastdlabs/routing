<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 下午10:47
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */


include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../src/FastD/Routing/Routes.php';

$router = new \FastD\Routing\Router();

$router->with('/demo', function () use ($router) {
    $router->addRoute('root', '/', null);
    $router->addRoute('root2', '/2', null);

    $router->with('/demo2', function () use ($router) {
        $router->addRoute('demoroot2', '/demo3', null);
    });
});

$router->addRoute('demo', '/', null);

echo '<pre>';
print_r($router);

/**
 * $router = new Router();
 * $router->addRoute(new Route());
 * $router->addGroup(new RouteGroup());
 * $router->get('/', 'name', function () {});
 * $router->match('/', Router::MATCH_ALL);
 * $response = $router->dispatch('name');
 */

/**
 * Composite pattern
 * Decorator pattern
 * Prototype pattern
 * Factory pattern
 */

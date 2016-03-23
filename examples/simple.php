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

use FastD\Routing\Route;
use FastD\Routing\RouteGenerator;

$route4 = new Route('/name/{name}', '3', ['name' => 'jan']);
$route4->setFormats(['json']);
echo RouteGenerator::generateUrl($route4, ['name' => 'janhuang'], 'json') . '<br />';
echo RouteGenerator::generateUrl($route4, ['name' => 'janhuang'], 'jsp');
//$this->assertEquals('/name/jan', RouteGenerator::generateUrl($route4));
//$this->assertEquals('/name/janhuang', RouteGenerator::generateUrl($route4, ['name' => 'janhuang']));
//$this->assertEquals('/name/janhuang.json', RouteGenerator::generateUrl($route4, ['name' => 'janhuang'], 'json'));
//$this->assertEquals('/name/janhuang.json', RouteGenerator::generateUrl($route4, ['name' => 'janhuang'], 'jsp'));


$router = new \FastD\Routing\Router();

$router->setRoute(new Route('/', 'root'));


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

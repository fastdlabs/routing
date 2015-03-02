<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/28
 * Time: 下午6:52
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * sf: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */
echo '<pre>';
$composer = include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/RouteController.php';

use Dobee\Routing\Annotation\RouteAnnotation;
use Dobee\Routing\Annotation\AnnotationContext;
use Dobee\Routing\Route;
use Dobee\Routing\Router;

$router = new Router();

$request = \Dobee\Http\Request::createGlobalRequest();

$annotation = new AnnotationContext(new RouteAnnotation('Examples\\RouteController'));

$router->setRoute(new Route($annotation->getRouteBag('demo')));

$router->setRoute(new Route($annotation->getRouteBag('test')));

$route = $router->match($request->getPathInfo());

print_r($route);


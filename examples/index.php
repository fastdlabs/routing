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

use Dobee\Annotation\AnnotationContext;
use Dobee\Routing\Annotation\RouteAnnotation;
use Dobee\Routing\Route;
use Dobee\Routing\Router;

$router = new Router();

$annotation = new AnnotationContext(new RouteAnnotation('Examples\\RouteController'));

$router->setRoute(new Route(
    $annotation->getParameters('demo')['route'],
    $annotation->getParameters('demo')['name'],
    $annotation->getParameters('demo')['prefix'],
    $annotation->getParameters('demo')['parameters'],
    $annotation->getParameters('demo')['method'],
    ($annotation->getParameters('demo')['defaults']),
    ($annotation->getParameters('demo')['requirements']),
    $annotation->getParameters('demo')['format']
));

$router->setRoute(new Route(
    $annotation->getParameters('test')['route'],
    $annotation->getParameters('test')['name'],
    $annotation->getParameters('test')['prefix'],
    $annotation->getParameters('test')['parameters'],
    ($annotation->getParameters('test')['method']),
    ($annotation->getParameters('test')['defaults']),
    ($annotation->getParameters('test')['requirements']),
    $annotation->getParameters('test')['format']
));

$request = \Dobee\Http\Request::createGlobalRequest();

echo $router->generateUrl('test');

$route = $router->match($request->getPathInfo());

$response = $route->getCallable();

print_r($response);


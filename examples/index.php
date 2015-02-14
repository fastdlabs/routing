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

use Dobee\Routing\Matcher\RouteMatcher;
use Dobee\Annotation\AnnotationContext;
use Dobee\Routing\Annotation\RouteAnnotation;
use Dobee\Routing\RouteCollection;
use Dobee\Routing\Route;

$annotation = new AnnotationContext(new RouteAnnotation('\\RouteController'));

$collections = new RouteCollection(new RouteMatcher());

$collections->addRoute(new Route(
    $annotation->getParameters('demo')['route'],
    $annotation->getParameters('demo')['name'],
    $annotation->getParameters('test')['prefix'],
    $annotation->getParameters('demo')['_controller'],
    $annotation->getParameters('demo')['_parameters'],
    $annotation->getParameters('demo')['method'],
    ($annotation->getParameters('demo')['defaults']),
    ($annotation->getParameters('demo')['requirements']),
    $annotation->getParameters('demo')['format']
));

$collections->addRoute(new Route(
    $annotation->getParameters('test')['route'],
    $annotation->getParameters('test')['name'],
    $annotation->getParameters('test')['prefix'],
    $annotation->getParameters('test')['_controller'],
    $annotation->getParameters('test')['_parameters'],
    ($annotation->getParameters('test')['method']),
    ($annotation->getParameters('test')['defaults']),
    ($annotation->getParameters('test')['requirements']),
    $annotation->getParameters('test')['format']
));

$request = \Dobee\Http\Request::createGlobalRequest();

$route = $collections->match($request->getPathInfo());

echo $collections->generateUrl('test', array('name' => 'helloworld'));

list($controller, $action) = explode('@', $route->getController());

$result = call_user_func_array(array(new $controller(), $action), $route->getParameters());

print_r($result);
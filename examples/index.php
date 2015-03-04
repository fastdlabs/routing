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

use Dobee\Routing\Annotation\AnnotationContext;
use Dobee\Routing\Router;
use Dobee\Routing\Route;

$finder = new \Dobee\Finder\Finder();

$controllers = $finder->name('Controller%')->in(__DIR__)->files();

$router = new Router();

$annotationContext = new AnnotationContext();

foreach ($controllers as $controller) {
    $annotation = $controller->getAnnotation()->getClassAnnotation();
    $methods = $controller->getAnnotation()->getMethodsAnnotation();
    foreach ($methods as $name => $value) {
        $parametersBag = $annotationContext->getRouteParametersBag($value);
        $parametersBag->setPrefix(isset($annotation['Route']['parameters'][0]) ? $annotation['Route']['parameters'][0] : "");
        $router->setRoute(new Route($parametersBag));
    }
}

$request = \Dobee\Http\Request::createGlobalRequest();

$route = $router->match($request->getPathInfo());

print_r($route);

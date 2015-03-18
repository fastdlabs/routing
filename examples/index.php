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

use Dobee\Routing\Router;

$router = new Router();

/*if ($router->getCaching()) {
    echo 'get for caching.<br />';
    print_r($router);
    die;
}*/


$routes = $router->getAnnotationParser()->getRoutes('/abc', 'Examples\\RouteController');

$route = $routes->getRoute('test');

$match = $router->match('/abc/ac', $route);

echo 'get for annotation.<br />';
print_r($router);

$router->setCaching();


/*$finder = new \Dobee\Finder\Finder();

$controllers = $finder->name('Controller%')->in(__DIR__)->files();

foreach ($controllers as $controller) {
    foreach ($annotationContext->getRoutes() as $route) {
        $router->setRoute($route);
    }*/

//    $annotation = $controller->getAnnotation()->getClassAnnotation();
//    $methods = $controller->getAnnotation()->getMethodsAnnotation();
//    print_r($methods);
//    foreach ($methods as $name => $value) {
//        echo '<pre>';
//        print_r($value);
//        echo '</pre>';
//        $parametersBag = $annotationContext->getRouteParametersBag($value);
//        $parametersBag->setPrefix(isset($annotation['Route']['parameters'][0]) ? $annotation['Route']['parameters'][0] : "");
//        $router->setRoute(new Route($parametersBag));
//    }
//}
//
//$request = \Dobee\Http\Request::createGlobalRequest();
//
//$route = $router->match($request->getPathInfo());
//
//echo $router->generateUrl('test', array('name' => 123));


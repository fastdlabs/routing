<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午3:19
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

echo '<pre>';
$composer = include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/RouteController.php';

use Dobee\Annotation\AnnotationContext;
use Dobee\Routing\Annotation\RouteAnnotation;
use Dobee\Routing\Route;
use Dobee\Routing\Router;

$router = new Router();

$router->get();

$router->post();

$router->any();

$router->head();

print_r($router);

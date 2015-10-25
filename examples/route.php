<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/24
 * Time: 上午12:25
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */


include __DIR__ . '/../vendor/autoload.php';

$route = new \FastD\Routing\Route('/', 'root');

echo '<pre>';

var_dump($route->getParameters() == []);
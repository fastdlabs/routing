<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


include_once __DIR__.  '/vendor/autoload.php';

use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

/*$collection->addRoute('test2', 'GET', '/[{name}]', function ($name) {
    return $name;
}, ['name' => 'janhuang']);

$result = $collection->dispatch('GET', '/');

$this->assertEquals('janhuang', $result);*/



$collection->addRoute('test', 'GET', '/users/[{name}]', function ($name) {
    return $name;
});

$route = $collection->match('GET', '/users');
print_r($route->getParameters());

preg_match('~^(?|/?([^/]*)|/users/?([^/]*)())$~', '/users', $match);
print_r($match);
//echo $collection->dispatch('GET', '/users') . PHP_EOL;


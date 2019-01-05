<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Routing\RouteCollection;

include __DIR__ . '/../vendor/autoload.php';

$collection = new RouteCollection();

$collection->addRoute();

$collection->match();
<?php

use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

include __DIR__ . '/vendor/autoload.php';

include __DIR__ . '/tests/handle/hello.php';
require_once __DIR__ . '/tests/middleware/AfterMiddleware.php';
require_once __DIR__ . '/tests/middleware/BeforeMiddleware.php';

$collections = new RouteCollection();

$collections->addRoute('GET', '/{name}', '');

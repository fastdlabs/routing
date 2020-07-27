<?php

use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

include __DIR__ . '/vendor/autoload.php';

include __DIR__ . '/tests/handle/hello.php';

$collections = new RouteCollection();

$collections->get('/')->handle(hello::class);

$dispatcher = new RouteDispatcher($collections);

$response = $dispatcher->dispatch(ServerRequest::createServerRequestFromGlobals());

$response->send();

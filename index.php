<?php

include __DIR__ . '/vendor/autoload.php';

include __DIR__ . '/tests/handle/hello.php';

$collections = new \FastD\Routing\RouteCollection();

$collections->get('/')->handle(hello::class);

$dispatcher = new \FastD\Routing\RouteDispatcher($collections);

$response = $dispatcher->dispatch(\FastD\Http\ServerRequest::createServerRequestFromGlobals());

$response->send();

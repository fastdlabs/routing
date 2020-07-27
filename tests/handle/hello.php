<?php

use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Middleware\DelegateInterface;
use FastD\Routing\Handle\RouteHandleInterface;
use Psr\Http\Message\ResponseInterface;

class hello implements RouteHandleInterface
{
    public function handle(ServerRequest $request, DelegateInterface $delegate): ResponseInterface
    {
        return $delegate->handle($request);
    }
}

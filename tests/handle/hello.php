<?php

class hello implements \FastD\Routing\Handle\RouteHandleInterface
{

    public function handle(\FastD\Http\ServerRequest $request, \FastD\Middleware\DelegateInterface $delegate): \Psr\Http\Message\ResponseInterface
    {
        return new \FastD\Http\Response("hello");
    }
}

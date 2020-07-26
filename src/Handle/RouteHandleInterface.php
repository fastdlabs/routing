<?php


namespace FastD\Routing\Handle;


use FastD\Http\ServerRequest;
use FastD\Middleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;

interface RouteHandleInterface
{
    public function handle(ServerRequest $request, DelegateInterface $delegate): ResponseInterface;
}
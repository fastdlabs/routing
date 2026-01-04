<?php

declare(strict_types=1);

namespace FastD\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements MiddlewareInterface
{
    public function __construct(protected Route $route)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return call_user_func_array($this->route->getHandler(), [$request, $handler]);
    }
}

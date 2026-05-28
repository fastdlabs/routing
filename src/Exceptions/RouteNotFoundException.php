<?php

declare(strict_types=1);

namespace FastD\Routing\Exceptions;

class RouteNotFoundException extends RouteException
{
    public function __construct(string $method, string $path)
    {
        parent::__construct(sprintf('Not found Route "%s" with "%s"', $path, $method), 404, null);
    }
}
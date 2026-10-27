<?php

declare(strict_types=1);

namespace FastD\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouteMatchInterface
{
    public function match(ServerRequestInterface $serverRequest): MatchedInterface;
}
<?php

declare(strict_types=1);

namespace FastD\Routing;

use FastD\Routing\Collection\Route;
use Psr\Http\Message\ServerRequestInterface;

interface MatchedInterface
{
    public function getRoute(): Route;

    public function getServerRequest(): ServerRequestInterface;
}
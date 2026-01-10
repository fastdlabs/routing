<?php

declare(strict_types=1);

namespace FastD\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouteMatchInterface
{
    /**
     * 如果未匹配到或者未执行匹配，返回 null
     *
     * @return MatchedInterface|null
     */
    public function getMatched(): ?MatchedInterface;

    public function match(ServerRequestInterface $serverRequest): MatchedInterface;
}
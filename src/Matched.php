<?php

declare(strict_types=1);

namespace FastD\Routing;

use FastD\Routing\Collection\Route;
use Psr\Http\Message\ServerRequestInterface;

class Matched implements MatchedInterface
{
    public function __construct(protected ServerRequestInterface $serverRequest, protected Route $route, protected array $vars)
    {
        // 设置匹配变量，保留预设默认和匹配变量
        $route->setMatchedVariables($this->vars);
        $vars = array_merge($route->getParameters()['definition'] ?? [], $this->vars);
        foreach ($vars as $name => $value) {
            // 以最终状态进行流转
            $this->serverRequest = $this->serverRequest->withAttribute($name, $value);
        }
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function getServerRequest(): ServerRequestInterface
    {
        return $this->serverRequest;
    }
}
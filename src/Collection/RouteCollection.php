<?php

declare(strict_types=1);

namespace FastD\Routing\Collection;

// 路由集合，集合路由地图，路由解析，开放添加路由的功能，统一管理路由的解析与增减
class RouteCollection
{
    protected string $currentGroupPrefix = '';

    protected array $currentGroupMiddleware = [];


    public function __construct(protected RouteParser $routeParser = new RouteParser(), public RouteMaps $routeMaps = new RouteMaps())
    {
    }

    public function addRoute(string $method, string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $path = $this->currentGroupPrefix . $path;
        $middleware = $this->currentGroupMiddleware + $middleware;
        $routeDatas = $this->routeParser->parse($path);
        foreach ($routeDatas as $routeData) {
            $this->routeMaps->addRoute($method, $routeData, $handler, $middleware, $parameters);
        }
    }

    public function group(string $prefix, callable $callable, array $middleware = []): RouteCollection
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $previousGroupMiddleware = $this->currentGroupMiddleware;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware + $middleware;
        $callable($this);
        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware;

        return $this;
    }

    public function get(string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware, $parameters);
    }

    public function post(string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware, $parameters);
    }

    public function put(string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $parameters);
    }

    public function patch(string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('PATCH', $path, $handler, $middleware, $parameters);
    }

    public function delete(string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $parameters);
    }

    public function options(string $path, $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('OPTIONS', $path, $handler, $middleware, $parameters);
    }
}

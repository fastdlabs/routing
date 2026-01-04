<?php

declare(strict_types=1);

namespace FastD\Routing;

class RouteCollection
{
    protected string $currentGroupPrefix = '';

    protected array $currentGroupMiddleware = [];


    public function __construct(protected RouteParser $routeParser = new RouteParser(), public RouteMaps $routeMaps = new RouteMaps())
    {
    }

    public function addRoute(string $method, string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $path = $this->currentGroupPrefix . $path;
        $middleware = $this->currentGroupMiddleware + $middleware;
        $routeDatas = $this->routeParser->parse($path);
        foreach ((array) $method as $value) {
            foreach ($routeDatas as $routeData) {
                $this->routeMaps->addRoute($value, $routeData, $handler, $middleware, $parameters);
            }
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

    public function get(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware, $parameters);
    }

    public function post(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware, $parameters);
    }

    public function put(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $parameters);
    }

    public function patch(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('PATCH', $path, $handler, $middleware, $parameters);
    }

    public function delete(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $parameters);
    }

    public function options(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('OPTIONS', $path, $handler, $middleware, $parameters);
    }
}

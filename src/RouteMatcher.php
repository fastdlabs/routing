<?php

declare(strict_types=1);

namespace FastD\Routing;

use FastD\Middleware\Dispatcher;
use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\Middleware\RouteMiddleware;
use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Closure;
use RuntimeException;
use Throwable;

class RouteMatcher extends Dispatcher implements RouteMatchInterface
{
    protected ?MatchedInterface $matched = null;

    public function __construct(
        public readonly RouteCollection $routeCollection = new RouteCollection(),
        protected ?Closure $resolver = null,
    ) {
        parent::__construct([]);
    }

    public function getMatched(): ?MatchedInterface
    {
        return $this->matched;
    }

    public function dispatch(ServerRequestInterface $serverRequest): ResponseInterface
    {
        // 匹配路由后，会更新路由参数、请求参数；PSR-7 请求以 clone 副本流转
        $matched = $this->match($serverRequest);

        // 请求作用域：本次 dispatch 使用独立 Dispatcher，避免并发请求
        // 共享 RouteMatcher 的 SplStack（Swoole 协程交错时会污染中间件栈）。
        $dispatcher = new Dispatcher([], clone $this->splStack);

        foreach ($matched->getRoute()->getMiddlewares() as $middleware) {
            if (!class_exists($middleware)) {
                throw new RuntimeException(sprintf('Middleware %s is not defined.', $middleware));
            }
            $dispatcher->push($this->resolve($middleware));
        }
        $dispatcher->push(new RouteMiddleware($matched, $this->resolver));

        return $dispatcher->dispatch($matched->getServerRequest());
    }

    private function resolve(string $class): object
    {
        return $this->resolver ? ($this->resolver)($class) : new $class();
    }

    public function match(ServerRequestInterface $serverRequest): MatchedInterface
    {
        $method = $serverRequest->getMethod();
        $path = $serverRequest->getUri()->getPath();
        [$staticRouteMap, $variableRoutes] = $this->routeCollection->routeMaps->getRoutes();

        $route = null;
        $vars = [];

        if (isset($staticRouteMap[$method][$path])) {
            $route = $staticRouteMap[$method][$path];
        }

        if (isset($variableRoutes[$method])) {
            $result = $this->matchVariableRoute($variableRoutes[$method], $path);
            if (!is_null($result[0])) {
                [$route, $vars] = $result;
            }
        }

        // If nothing else matches, try fallback routes
        if (isset($staticRouteMap['*'][$path])) {
            $route = $staticRouteMap['*'][$path];
        }

        if (isset($variableRoutes['*'])) {
            $result = $this->matchVariableRoute($variableRoutes['*'], $path);
            if (!is_null($result[0])) {
                [$route, $vars] = $result;
            }
        }

        if (null === $route) {
            throw new RouteNotFoundException($serverRequest->getMethod(), $serverRequest->getUri()->getPath());
        }

        $this->matched = new Matched($serverRequest, $route, $vars);

        return $this->matched;
    }

    protected function matchVariableRoute(array $routeData, string $uri): array
    {
        foreach ($routeData as $data) {
            if (!preg_match($data['regex'], $uri, $matches)) {
                continue;
            }

            $route = $data['routeMap'][$matches['MARK']];

            $definedVars = $route->getVariables();
            $matchedVars = [];
            $i = 0;
            foreach ($definedVars as $varName) {
                $matchedVars[$varName] = $matches[++$i];
            }

            return [$route, $matchedVars];
        }

        return [null];
    }
}
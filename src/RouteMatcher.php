<?php

declare(strict_types=1);

namespace FastD\Routing;

use FastD\Middleware\Dispatcher;
use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\Middleware\RouteMiddleware;
use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Throwable;

class RouteMatcher extends Dispatcher implements RouteMatchInterface
{
    protected ?MatchedInterface $matched = null;

    public function __construct(protected RouteCollection $routeCollection = new RouteCollection)
    {
        parent::__construct([]);
    }

    public function getMatched(): ?MatchedInterface
    {
        return $this->matched;
    }

    public function dispatch(ServerRequestInterface $serverRequest): ResponseInterface
    {
        // 匹配路由后，会更新路由参数，请求参数，根据 psr7的约定，request 会以 clone 副本的方式返回，也就是与传入的可能存产生一定的差异，需要以 matched 的对象为准
        $matched = $this->match($serverRequest);

        // 采用 clone 副本的方式，主要为了兼容 swoole 模式下，堆栈会被不断消费的问题
        $prototypeStack = clone $this->splStack;
        // wrapper route middleware
        foreach ($matched->getRoute()->getMiddlewares() as $key => $middleware) {
            if (!class_exists($middleware)) {
                throw new RuntimeException(sprintf('Middleware %s is not defined.', $middleware));
            }
            $this->push(new $middleware);
        }
        $this->push(new RouteMiddleware($matched));

        // 调用完成后则重设中间间副本
        try {
            $response = parent::dispatch($matched->getServerRequest());
            $this->splStack = $prototypeStack;
            unset($prototypeStack);
            return $response;
        } catch (Throwable $throwable) {
            $this->splStack = $prototypeStack;
            unset($prototypeStack);
            throw $throwable;
        }
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
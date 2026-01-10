<?php

namespace FastD\Routing;

use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\Dispatcher\Matched;
use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class RouteMatch
{
    protected ?MatchedInterface $matched = null;

    public function __construct(protected RouteCollection $routeCollection = new RouteCollection)
    {
    }
    public function match(ServerRequestInterface $serverRequest): Matched
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
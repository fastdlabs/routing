<?php
declare(strict_types=1);

namespace FastD\Routing;

use Exception;
use FastD\Middleware\Dispatcher;
use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteDispatcher extends Dispatcher
{
    protected Route $activeRoute;

    public function __construct(protected RouteCollection $routeCollection = new RouteCollection)
    {
        parent::__construct([]);
    }

    public function getRouteCollection(): RouteCollection
    {
        return $this->routeCollection;
    }

    public function getActiveRoute(): Route
    {
        return $this->activeRoute;
    }

    public function dispatch(ServerRequestInterface $serverRequest): ResponseInterface
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
            $result = $this->dispatchVariableRoute($variableRoutes[$method], $path);
            if (!is_null($result[0])) {
                [$route, $vars] = $result;
            }
        }

        // If nothing else matches, try fallback routes
        if (isset($staticRouteMap['*'][$path])) {
            $route = $staticRouteMap['*'][$path];
        }

        if (isset($variableRoutes['*'])) {
            $result = $this->dispatchVariableRoute($variableRoutes['*'], $path);
            if (!is_null($result[0])) {
                [$route, $vars] = $result;
            }
        }

        if (null === $route) {
            throw new RouteNotFoundException($serverRequest->getMethod(), $serverRequest->getUri()->getPath());
        }

        $vars = array_merge($route->getParameters(), $vars);
        $route->setParameters($vars);
        foreach ($vars as $key => $var) {
            $serverRequest->withAttribute($key, $var);
        }

        return $this->dispatchMiddleware($route, $serverRequest);
    }

    protected function dispatchVariableRoute(array $routeData, string $uri): array
    {
        foreach ($routeData as $data) {
            if (!preg_match($data['regex'], $uri, $matches)) {
                continue;
            }

            $route = $data['routeMap'][$matches['MARK']];

            $vars = [];
            $i = 0;
            foreach ($route->variables as $varName) {
                $vars[$varName] = $matches[++$i];
            }

            return [$route, $vars];
        }

        return [null];
    }

    protected function dispatchMiddleware(Route $route, ServerRequestInterface $serverRequest): ResponseInterface
    {
        $this->activeRoute = $route;
        $prototypeStack = clone $this->splStack;
        // wrapper route middleware
        foreach ($route->getMiddlewares() as $key => $middleware) {
            if (!class_exists($middleware)) {
                throw new \RuntimeException(sprintf('Middleware %s is not defined.', $middleware));
            }
            $this->push(new $middleware);
        }

        $this->push(new RouteMiddleware($route));

        try {
            $response = parent::dispatch($serverRequest);
            $this->splStack = $prototypeStack;
            unset($prototypeStack);
        } catch (Exception $exception) {
            $this->splStack = $prototypeStack;
            unset($prototypeStack);
            throw $exception;
        }

        return $response;
    }
}

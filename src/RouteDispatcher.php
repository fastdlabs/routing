<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use Exception;
use FastD\Middleware\Dispatcher;
use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteDispatcher
 * @package FastD\Routing
 */
class RouteDispatcher extends Dispatcher
{
    /**
     * @var RouteCollection
     */
    protected RouteCollection $routeCollection;


    protected Route $activeRoute;

    /**
     * RouteDispatcher constructor.
     *
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;

        parent::__construct([]);
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->routeCollection;
    }

    /**
     * @return Route
     */
    public function getActiveRoute() :Route
    {
        return $this->activeRoute;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function dispatch(ServerRequestInterface $requestHandler): ResponseInterface
    {
        $method = $requestHandler->getMethod();
        $path = $requestHandler->getUri()->getPath();
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
            throw new RouteNotFoundException($requestHandler->getMethod(), $requestHandler->getUri()->getPath());
        }

        $vars = array_merge($route->getParameters(), $vars);
        $route->setParameters($vars);
        foreach ($vars as $key => $var) {
            $requestHandler->withAttribute($key, $var);
        }

        return $this->dispatchMiddleware($route, $requestHandler);
    }

    /**
     * @param array $routeData
     * @param string $uri
     * @return array
     */
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

    /**
     * @param Route $route
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    protected function dispatchMiddleware(Route $route, ServerRequestInterface $requestHandler): ResponseInterface
    {
        $this->activeRoute = $route;
        $prototypeStack = clone $this->stack;
        // wrapper route middleware
        foreach ($route->getMiddlewares() as $key => $middleware) {
            if (!class_exists($middleware)) {
                throw new \RuntimeException(sprintf('Middleware %s is not defined.', $middleware));
            }
            $this->push(new $middleware);
        }

        $this->push(new RouteMiddleware($route));

        try {
            $response = parent::dispatch($requestHandler);
            $this->stack = $prototypeStack;
            unset($prototypeStack);
        } catch (Exception $exception) {
            $this->stack = $prototypeStack;
            unset($prototypeStack);
            throw $exception;
        }

        return $response;
    }
}

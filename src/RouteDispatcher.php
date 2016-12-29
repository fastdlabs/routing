<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;
use FastD\Middleware\Dispatcher;
use FastD\Middleware\ServerMiddleware;
use FastD\Middleware\ServerMiddlewareInterface;
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
    protected $routeCollection;

    /**
     * RouteDispatcher constructor.
     *
     * @param RouteCollection $routeCollection
     * @param $stack
     */
    public function __construct(RouteCollection $routeCollection, $stack = [])
    {
        $this->routeCollection = $routeCollection;

        parent::__construct($stack);
    }

    /**
     * @param Route $route
     * @return ServerMiddlewareInterface
     */
    protected function createRouteMiddleware(Route $route)
    {
        return new ServerMiddleware(function (ServerRequest $request, Delegate $next) use ($route) {
            $response = call_user_func_array($route->getCallback(), $route->getParameters());
            try {
                return $next($request);
            } catch (\Exception $e) {
                return $response;
            }
        });
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $route = $this->routeCollection->match($request->getMethod(), $request->getUri()->getPath());
        // scan all middleware
        foreach ($route->getMiddleware() as $middleware) {
            $this->stack->withoutMiddleware($middleware);
        }

        $this->stack->withoutMiddleware($this->createRouteMiddleware($route));

        return parent::dispatch($request);
    }
}
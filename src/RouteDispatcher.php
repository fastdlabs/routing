<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Middleware\Dispatcher;
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
     * @var array
     */
    protected $stackMap = [];

    /**
     * RouteDispatcher constructor.
     *
     * @param RouteCollection $routeCollection
     * @param $stack
     */
    public function __construct(RouteCollection $routeCollection, $stack = [])
    {
        $this->routeCollection = $routeCollection;

        $this->stackMap = $stack;

        parent::__construct([]);
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $route = $this->routeCollection->match($request);

        // set middleware list
        foreach ($route->getMiddlewareKey() as $key) {
            $middlewareList = $this->stackMap[$key];
            if (is_array($middlewareList)) {
                foreach ($this->stackMap[$key] as $middleware) {
                    $this->stack->withAddMiddleware($middleware);
                }
            } else {
                $this->stack->withAddMiddleware($middlewareList);
            }
        }

        // append route middleware
        foreach ($route->getMiddleware() as $middleware) {
            $this->stack->withAddMiddleware($middleware);
        }

        // wrapper route middleware
        $this->stack->withAddMiddleware(new RouteMiddleware($route));

        return parent::dispatch($request);
    }
}
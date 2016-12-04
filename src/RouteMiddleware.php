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
use FastD\Middleware\ServerMiddleware;

/**
 * Class RouteMiddleware
 * @package FastD\Routing
 */
class RouteMiddleware extends ServerMiddleware
{
    /**
     * @var RouteCollection
     */
    protected $callback;

    /**
     * RouteMiddleware constructor.
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        parent::__construct(function (ServerRequest $request, Delegate $next) use ($routeCollection) {
            return $routeCollection->match($request->getMethod(), str_replace('//', '/', $request->getUri()->getPath()));
        });
    }
}
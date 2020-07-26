<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Middleware\DelegateInterface;
use FastD\Middleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteMiddleware
 * @package FastD\Routing
 */
class RouteMiddleware implements MiddlewareInterface
{
    const HANDLE = 'handle';

    /**
     * @var Route
     */
    protected Route $route;

    /**
     * RouteMiddleware constructor.
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next): ResponseInterface
    {
        return call_user_func_array([$this->route->getHandle(), static::HANDLE], [$request, $next]);
    }
}

<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteMiddleware
 * @package FastD\Routing
 */
class RouteMiddleware extends Middleware
{
    /**
     * @var Route
     */
    protected $route;

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
    public function handle(ServerRequestInterface $request, DelegateInterface $next = null)
    {
        if (is_string(($callback = $this->route->getCallback()))) {
            if (false !== strpos($callback, '@')) {
                list($class, $mhandlhandlefffethod) = explode('@', $callback);
            } else {
                $class = $callback;
                $method = 'handle';
            }
            $response = call_user_func_array([new $class, $method], [$request, $next]);
        } else if (is_callable($callback)) {
            $response = call_user_func_array($callback, [$request, $next]);
        } else if (is_array($callback)) {
            $class = $callback[0];
            if (is_string($class)) {
                $class = new $class;
            }
            $response = call_user_func_array([$class, $callback[1]], [$request, $next]);
        } else {
            $response = new Response('Don\'t support callback, Please setting callable function or class@method.');
        }
        unset($callback);

        return $response;
    }
}
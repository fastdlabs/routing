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
     * @param ServerRequestInterface $serverRequest
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $serverRequest, DelegateInterface $delegate)
    {
        if (is_string(($callback = $this->route->getCallback()))) {
            list($class, $method) = explode('@', $callback);
            $response = call_user_func_array([new $class, $method], [$serverRequest, $delegate]);
        } else if (is_callable($callback)) {
            $response = call_user_func_array($callback, [$serverRequest, $delegate]);
        } else if (is_array($callback)) {
            $class = $callback[0];
            if (is_string($class)) {
                $class = new $class;
            }
            $response = call_user_func_array([$class, $callback[1]], [$serverRequest, $delegate]);
        } else {
            $response = new Response('Don\'t support callback, Please setting callable function or class@method.');
        }
        unset($callback);

        return $response;
    }
}
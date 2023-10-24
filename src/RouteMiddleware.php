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


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouteMiddleware
 * @package FastD\Routing
 */
class RouteMiddleware implements MiddlewareInterface
{
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
     * @param RequestHandlerInterface $requestHandler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $handler = $this->route->getHandler();
        if (count($handler) == 1) {
            $handler = $handler[0];
        }
        return call_user_func_array($handler, [$request, $requestHandler]);
    }
}

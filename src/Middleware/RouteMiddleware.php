<?php

declare(strict_types=1);

namespace FastD\Routing\Middleware;

use FastD\Routing\Collection\Route;
use FastD\Routing\Exceptions\RouteCallbackException;
use FastD\Routing\MatchedInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements MiddlewareInterface
{
    public function __construct(protected MatchedInterface $matched)
    {
    }

    private function formattedRouteHandler($handler): array
    {
        if (is_string($handler)) {
            // 处理控制器@方法格式
            if (str_contains($handler, '@')) {
                [$className, $methodName] = explode('@', $handler, 2);
                if (class_exists($className) && method_exists($className, $methodName)) {
                    return [
                        'type' => 'class',
                        'handler' => [new $className, $methodName],
                    ];
                }
                throw new RouteCallbackException(sprintf('Handler %s or method %s does not exist', $className, $methodName));
            }

            // 处理中间件类
            if (class_exists($handler) && is_subclass_of($handler, MiddlewareInterface::class)) {
                return [
                    'type' => 'middleware',
                    'handler' => [new $handler, 'process']
                ];
            }

            // 处理函数名
            if (function_exists($handler)) {
                return [
                    'type' => 'function',
                    'handler' => $handler
                ];
            }

            throw new RouteCallbackException(sprintf('Handler %s is not a valid callback', $handler));
        }

        if (is_callable($handler)) {
            return [
                'type' => 'callable',
                'handler' => $handler
            ];
        }

        throw new RouteCallbackException(sprintf('Invalid handler type: %s', gettype($handler)));
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeHandler = $this->formattedRouteHandler($this->matched->getRoute()->getHandler());
        // 除了 middleware 之外，其他类型参数可以动态注入
//        $parameters = 'middleware' === $routeHandler['type'] ? [$request, $handler] : array_merge([$request, $handler], $request->getAttributes());
        return call_user_func_array($routeHandler['handler'],  [$request, $handler]);
    }
}
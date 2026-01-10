<?php

declare(strict_types=1);

namespace FastD\Routing;

use FastD\Routing\Exceptions\CallbackException;
use Psr\Http\Server\MiddlewareInterface;

class Route
{
    public function __construct(
        protected string $method,
        protected string $handler,
        public    string $regex,
        public    array  $variables,
        protected array  $middlewares = [],
        protected array  $parameters = []
    )
    {
    }

    public function matches(string $str): bool
    {
        $regex = '~^' . $this->regex . '$~';

        return (bool) preg_match($regex, $str);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): Route
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function addMiddleware(string $middleware): Route
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    public function setMiddlewares(array $middlewares): Route
    {
        $this->middlewares = $middlewares;

        return $this;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getHandler(): array
    {
        if (!str_contains($this->handler, '@')) {
            if (function_exists($this->handler)) {
                return [$this->handler];
            }
            $handler = new $this->handler;
            if (!($handler instanceof MiddlewareInterface)) {
                throw new CallbackException(sprintf('Route callback must be instance of %s', MiddlewareInterface::class));
            }
            return [$handler, 'process'];
        }

        [$handler, $callback] = explode('@', $this->handler);
        return [new $handler, $callback];
    }
}

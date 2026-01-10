<?php

declare(strict_types=1);

namespace FastD\Routing\Collection;

// 路由个体，只做基础存储，不做过多解析
class Route
{
    protected array $parameters = [];

    // 路由一旦确立之后，属性就不能通过外部进行变更，仅保留初始化赋值
    public function __construct(
        protected string $method,
        protected mixed  $handler,
        protected string $regex,
        protected array  $variables,
        protected array  $middlewares = [], // 可变
        array $definition = [] // 默认变量参数
    )
    {
        $this->parameters['definition'] = $definition;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    // 设置匹配变量，同时保留原始变量
    public function setMatchedVariables(array $variables): Route
    {
        $this->parameters['matched'] = $variables;

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

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function match(string $str): bool
    {
        $regex = '~^' . $this->regex . '$~';

        return (bool) preg_match($regex, $str);
    }
}

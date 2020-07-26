<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use FastD\Routing\Traits\ResourcesTrait;

/**
 * Class RouteCollection
 *
 * @package FastD\Routing
 */
class RouteCollection
{
    use ResourcesTrait;

    const ROUTES_CHUNK = 10;

    /**
     * @var array
     */
    protected array $prefix = [];

    /**
     * @var array
     */
    protected array $middleware = [];

    /**
     * @var Route
     */
    protected Route $activeRoute;

    /**
     * @var int
     */
    protected int $num = 1;

    /**
     * 路由分组计数器
     *
     * @var int
     */
    protected int $index = 0;

    /**
     * @var array
     */
    protected array $regexes = [];

    /**
     * @var Route[]
     */
    public array $staticRoutes = [];

    /**
     * @var Route[]
     */
    public array $dynamicRoutes = [];

    /**
     * @var array
     */
    public array $aliasMap = [];

    /**
     * @param string $path
     * @return RouteCollection
     */
    public function prefix(string $path): RouteCollection
    {
        array_push($this->prefix, $path);

        return $this;
    }

    /**
     * @param string $middleware
     * @return RouteCollection
     */
    public function middleware(string $middleware): RouteCollection
    {
        array_push($this->middleware, $middleware);

        return $this;
    }

    /**
     * @param callable $callable
     * @return RouteCollection
     */
    public function group(callable $callable): RouteCollection
    {
        $callable($this);

        $this->restorePrefix();
        $this->restoreMiddleware();

        return $this;
    }

    /**
     * @return Route
     */
    public function getActiveRoute(): Route
    {
        return $this->activeRoute;
    }

    /**
     * @param string $method
     * @param string $path
     * @return Route
     */
    public function addRoute(string $method, string $path): Route
    {
        $path = implode('/', $this->prefix).$path;

        $route = new Route($method, $path);

        $method = $route->getMethod();

        if (isset($this->aliasMap[$method][$path])) {
            return $this->aliasMap[$method][$path];
        }

        $route->setMiddleware($this->middleware);

        if ($route->isStatic()) {
            $this->staticRoutes[$method][$path] = $route;
        } else {
            $numVariables = count($route->getVariables());
            $numGroups = max($this->num, $numVariables);
            $this->regexes[$method][] = $route->getRegex().str_repeat('()', $numGroups - $numVariables);

            $this->dynamicRoutes[$method][$this->index]['regex'] = '~^(?|'.implode('|', $this->regexes[$method]).')$~';
            $this->dynamicRoutes[$method][$this->index]['routes'][$numGroups + 1] = $route;

            ++$this->num;

            if (count($this->regexes[$method]) >= static::ROUTES_CHUNK) {
                ++$this->index;
                $this->num = 1;
                $this->regexes[$method] = [];
            }
            unset($numGroups, $numVariables);
        }

        $this->aliasMap[$method][$path] = $route;

        return $route;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Route
     * @throws RouteNotFoundException
     */
    public function match(ServerRequestInterface $serverRequest): Route
    {
        $method = $serverRequest->getMethod();
        $path = $serverRequest->getUri()->getPath();

        if (isset($this->staticRoutes[$method][$path])) {
            return $this->activeRoute = $this->staticRoutes[$method][$path];
        } else {
            $possiblePath = $path;
            if ('/' === substr($possiblePath, -1)) {
                $possiblePath = rtrim($possiblePath, '/');
            } else {
                $possiblePath .= '/';
            }
            if (isset($this->staticRoutes[$method][$possiblePath])) {
                return $this->activeRoute = $this->staticRoutes[$method][$possiblePath];
            }
            unset($possiblePath);
        }

        if (
            ! isset($this->dynamicRoutes[$method])
            || false === $route = $this->matchDynamicRoute($serverRequest, $method, $path)
        ) {
            throw new RouteNotFoundException($method, $path);
        }

        return $this->activeRoute = $route;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @param $method
     * @param $path
     * @return Route
     */
    protected function matchDynamicRoute(ServerRequestInterface $serverRequest, $method, $path): Route
    {
        foreach ($this->dynamicRoutes[$method] as $data) {
            if (!preg_match($data['regex'], $path, $matches)) {
                continue;
            }
            $route = $data['routes'][count($matches)];
            preg_match('~^'.$route->getRegex().'$~', $path, $match);
            $match = array_slice($match, 1, count($route->getVariables()));
            $attributes = array_combine($route->getVariables(), $match);
            $attributes = array_filter($attributes);
            $route->mergeParameters($attributes);
            foreach ($route->getParameters() as $key => $attribute) {
                $serverRequest->withAttribute($key, $attribute);
            }

            return $route;
        }

        throw new RouteNotFoundException($method, $path);
    }

    protected function restoreMiddleware(): void
    {
        array_pop($this->middleware);
    }

    protected function restorePrefix(): void
    {
        array_pop($this->prefix);
    }
}

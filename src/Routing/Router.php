<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/28
 * Time: 下午7:10
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * sf: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Routing;

/**
 * Class Router
 *
 * @package FastD\Routing
 */
class Router extends RouteCollection
{
    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var array
     */
    protected $group = [];

    /**
     * @param          $path
     * @param callable $callback
     */
    public function group($path, callable $callback)
    {
        array_push($this->with, $path);

        $callback($this);

        array_pop($this->with);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @return mixed
     */
    public function dispatch($method, $path, array $parameters = [])
    {
        return call_user_func_array($this->match($method, $path), $parameters);
    }

    /**
     * @param $name
     * @param array $parameters
     * @param null $format
     * @return string
     * @throws \Exception
     */
    public function generateUrl($name, array $parameters = [], $format = null)
    {
        $route = $this->getRoute($name);

        $parameters = array_merge($route->getDefaults(), $parameters);

        $host = '' == $route->getHost() ? '' : ($route->getScheme() . '://' . $route->getHost());

        if (array() === $route->getParameters()) {
            if (substr($route->getPath(), -1) != '/' && in_array($format, $route->getFormats())) {
                $format = '.' . $format;
            } else {
                $format = '';
            }
            return $host . $route->getPath() . $format . (array() === $parameters ? '' : '?' . http_build_query($parameters));
        }

        $replacer = $parameters;
        $keys = array_keys($parameters);
        $search = array_map(
            function ($name) use (&$parameters) {
                unset($parameters[$name]);

                return '{' . $name . '}';
            },
            $keys
        );

        unset($keys);

        $routeUrl = str_replace($search, $replacer, $route->getPath());

        if (!preg_match($route->getPathRegex(), $routeUrl, $match)) {
            throw new \Exception(
                sprintf(
                    'Route "%s" generator fail. Your should set route parameters ["%s"] value.',
                    $route->getName(),
                    implode('", "', array_keys($route->getParameters()))
                ), 400
            );
        }

        if (substr($routeUrl, -1) !== '/' && in_array($format, $route->getFormats())) {
            $format = '.' . $format;
        } else {
            $format = '';
        }

        unset($route);

        return $host . $routeUrl . $format . (array() === $parameters ? '' : '?' . http_build_query($parameters));
    }
}

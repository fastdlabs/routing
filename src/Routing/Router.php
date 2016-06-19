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
     * @var Route
     */
    protected $route;

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var array
     */
    protected $group = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->route = new Route(null, null, null);
    }

    /**
     * @return array
     */
    public function getGroup()
    {
        return $this->group;
    }

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
     * @param $name
     * @param $method
     * @param $path
     * @param null $callback
     * @param array $defaults
     * @param array $requirements
     * @return Route
     */
    public function addRoute($name, $method, $path, $callback = null, array $defaults = [], array $requirements = [])
    {
        $with = implode('', $this->with);

        if (empty($with)) {
            $with = '/';
        }

        $path = str_replace('//', '/', $with . $path);

        $route = clone $this->route;
        $route->setDefaults($defaults);
        $route->setRequirements($requirements);
        $route->setMethod($method);
        $route->setPath($path);
        $route->setName($name);
        $route->setGroup($with);
        $route->setCallback($callback);

        $this->setRoute($route);
        $this->group[$with][] = $route->getName();

        return $this->getCurrentRoute();
    }

    /**
     * @param $method
     * @param $path
     * @return Route
     * @throws \Exception
     */
    public function match($method, $path)
    {
        try {
            $alias = $path . ':' . strtolower($method);
            return $this->getRoute($alias);
        } catch (\Exception $e) {
            $match = function ($path, Route $route) {
                if (!preg_match($route->getPathRegex(), $path, $match)) {
                    if (array() === $route->getParameters() || array() === $route->getDefaults()) {
                        return false;
                    }

                    $parameters = array_slice(
                        $route->getDefaults(),
                        (substr_count($path, '/') - substr_count($route->getPath(), '/'))
                    );

                    $path = str_replace('//', '/', $path . '/' . implode('/', array_values($parameters)));

                    unset($parameters);

                    if (!preg_match($route->getPathRegex(), $path, $match)) {
                        return false;
                    }
                }

                $data = [];
                foreach ($route->getParameters() as $key => $value) {
                    if (!empty($match[$key])) {
                        $data[$key] = $match[$key];
                    }
                }
                $route->mergeParameters($data);

                return true;
            };

            foreach ($this as $route) {
                if (true === $match($path, $route)) {
                    unset($match);
                    return $route;
                }
            }
        }

        throw new \Exception(sprintf('Not found "%s"', $path), 404);
    }

    /**
     * @param string $method
     * @param $path
     * @return mixed
     */
    public function dispatch($method, $path)
    {
        $callable = $this->match($method, $path)->getCallback();
        
        return $callable();
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

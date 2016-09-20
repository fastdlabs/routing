<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

/**
 * Class RouteCache
 *
 * @package FastD\Routing
 */
class RouteCache
{
    const CACHE = '.route.cache';

    protected $routes = [];

    protected $dir;

    public function __construct(RouteCollection $routeCollection, $dir = null)
    {
        $this->dir = $this->targetDirectory($dir);
    }

    protected function targetDirectory($dir)
    {
        if (null === $dir) {
            return false;
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    public function dump()
    {
        return var_export($this->routes, true);
    }

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    public static function __set_state($an_array)
    {

    }
}
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
    protected $routes = [];

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
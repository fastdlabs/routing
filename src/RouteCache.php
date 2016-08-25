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
    /**
     * @param RouteCollection $collection
     * @return string
     */
    public static function toCache(RouteCollection $collection)
    {
        return var_export($collection, true);
    }

    public static function __set_state($an_array)
    {

    }
}
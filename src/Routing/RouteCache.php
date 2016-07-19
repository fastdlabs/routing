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

class RouteCache extends RouteCollection
{
    public function toCache()
    {
        return var_export($this->getMap(), true);
    }

    public function toRoute()
    {

    }
}
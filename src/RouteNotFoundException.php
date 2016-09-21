<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

use FastD\Routing\RouteException;

/**
 * Class RouteNotFoundException
 *
 * @package FastD\Routing
 */
class RouteNotFoundException extends RouteException
{
    /**
     * RouteNotFoundException constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('Route "%s" is not found.', $path), 404, null);
    }
}
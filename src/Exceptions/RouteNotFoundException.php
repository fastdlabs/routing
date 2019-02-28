<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Exceptions;

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
     * @param string $method
     * @param string $path
     */
    public function __construct(string $method, string $path)
    {
        parent::__construct(sprintf('Not found Route "%s" with "%s"', $path, $method), 404, null);
    }
}
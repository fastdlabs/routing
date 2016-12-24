<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing\Exceptions;

use FastD\Routing\Route;

class RouteNotAllowException extends RouteException
{
    public function __construct(Route $route)
    {

    }
}
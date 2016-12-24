<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class AfterMiddleware extends \FastD\Middleware\ServerMiddleware
{
    public function __construct()
    {
        parent::__construct(function (\FastD\Http\Request $request, \FastD\Middleware\Delegate $next) {
            $str = 'after';
            echo $str;
            return $next($request);
        });
    }
}
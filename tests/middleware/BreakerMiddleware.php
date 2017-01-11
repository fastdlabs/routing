<?php
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;
use FastD\Middleware\ServerMiddleware;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class BreakerMiddleware extends ServerMiddleware
{
    public function __construct()
    {
        parent::__construct(function (ServerRequest $request, Delegate $next) {
            if ('break' == $request->getAttribute('name')) {
                return new Response('break');
            }
            return $next($request);
        });
    }
}
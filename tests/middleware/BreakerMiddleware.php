<?php

use FastD\Http\Response;
use FastD\Middleware\Middleware;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class BreakerMiddleware extends Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     * @param \FastD\Middleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(\Psr\Http\Message\ServerRequestInterface $serverRequest, \Psr\Http\Server\RequestHandlerInterface $delegate): \Psr\Http\Message\ResponseInterface
    {
        if ('break' == $serverRequest->getAttribute('name')) {
            return new Response('break');
        }

        return $delegate->handle($serverRequest)->withHeader('hello', 'world');
    }
}

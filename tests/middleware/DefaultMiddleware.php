<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class DefaultMiddleware extends \FastD\Middleware\Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     * @param \FastD\Middleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(\Psr\Http\Message\ServerRequestInterface $serverRequest, \Psr\Http\Server\RequestHandlerInterface $delegate): \Psr\Http\Message\ResponseInterface
    {
         return new \FastD\Http\Response('default');
    }
}

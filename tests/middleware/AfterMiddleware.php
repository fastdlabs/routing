<?php

use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class AfterMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $serverRequest, \Psr\Http\Server\RequestHandlerInterface $delegate): ResponseInterface
    {
        $str = 'after' . PHP_EOL;
        echo $str;
        return $delegate->handle($serverRequest);
    }
}

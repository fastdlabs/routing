<?php

use Psr\Http\Message\ResponseInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class BeforeMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(\Psr\Http\Message\ServerRequestInterface $serverRequest, \Psr\Http\Server\RequestHandlerInterface $delegate): ResponseInterface
    {
        $str = 'before' . PHP_EOL;
        echo $str;
        return $delegate->handle($serverRequest);
    }
}

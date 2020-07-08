<?php

use Psr\Http\Message\ResponseInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class AfterMiddleware extends \FastD\Middleware\Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     * @param \FastD\Middleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $serverRequest, \FastD\Middleware\DelegateInterface $delegate): ResponseInterface
    {
        $str = 'after' . PHP_EOL;
        echo $str;
        return $delegate->handle($serverRequest);
    }
}

<?php

/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */
class GlobalMiddleware extends \FastD\Middleware\Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     * @param \FastD\Middleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $serverRequest, \FastD\Middleware\DelegateInterface $delegate)
    {
        echo 'global' . PHP_EOL;

        return $delegate($serverRequest);
    }
}

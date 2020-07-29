<?php
declare(strict_types=1);

namespace FastD\Routing\Handle;


use FastD\Http\ServerRequest;
use FastD\Middleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;

interface RouteHandleInterface
{
    /**
     * 陈显双-道哥 支持的模式
     *
     * @param ServerRequest $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequest $request, DelegateInterface $delegate): ResponseInterface;
}

<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Routing\Resource;


use FastD\Middleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractSelectResource extends ResourceInterface
{
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        return parent::handle($request, $next)->withStatus(200);
    }
}
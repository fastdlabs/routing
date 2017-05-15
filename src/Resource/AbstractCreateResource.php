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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractCreateResource
 * @package FastD\Routing\Resource
 */
abstract class AbstractCreateResource extends ResourceInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $next)
    {
        return parent::process($request, $next)->withStatus(201);
    }
}
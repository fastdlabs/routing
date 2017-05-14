<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Routing\Resource;


use FastD\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ResourceInterface
 * @package FastD\Routing
 */
abstract class ResourceInterface extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     */
    abstract public function data(ServerRequestInterface $request);
}
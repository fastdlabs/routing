<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Routing\Traits;

trait ResourcesTrait
{

    /**
     * Adds a GET route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function get(string $path, $handler, array $middleware = [], array $parameters = [])
    {
        $this->addRoute('GET', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a POST route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function post(string $path, $handler, array $middleware = [], array $parameters = [])
    {
        $this->addRoute('POST', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a PUT route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function put(string $path, $handler, array $middleware = [], array $parameters = [])
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a PATCH route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function patch(string $path, $handler, array $middleware = [], array $parameters = [])
    {
        $this->addRoute('PATCH', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a DELETE route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function delete(string $path, $handler, array $middleware = [], array $parameters = [])
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a OPTIONS route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     * @return mixed
     */
    public function options(string $path, $handler, array $middleware = [], array $parameters = [])
    {
        $this->addRoute('OPTIONS', $path, $handler, $middleware, $parameters);
    }
}
<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Routing\Traits;


use FastD\Routing\Route;

trait ResourcesTrait
{

    /**
     * Adds a GET route to the collection
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function get(string $path, $handler): Route
    {
        return $this->addRoute('GET', $path, $handler);
    }

    /**
     * Adds a POST route to the collection
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function post(string $path, $handler): Route
    {
        return $this->addRoute('POST', $path, $handler);
    }

    /**
     * Adds a PUT route to the collection
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function put(string $path, $handler): Route
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Adds a PATCH route to the collection
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function patch(string $path, $handler): Route
    {
        return $this->addRoute('PATCH', $path, $handler);
    }

    /**
     * Adds a DELETE route to the collection
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function delete(string $path, $handler): Route
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Adds a OPTIONS route to the collection
     * @param string $path
     * @param $handler
     * @return mixed
     */
    public function options(string $path, $handler): Route
    {
        return $this->addRoute('OPTIONS', $path, $handler);
    }
}
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
    public function get($path): Route
    {
        return $this->addRoute('GET', $path);
    }

    public function post($path): Route
    {
        return $this->addRoute('POST', $path);
    }

    public function put($path): Route
    {
        return $this->addRoute('PUT', $path);
    }

    public function patch($path): Route
    {
        return $this->addRoute('PATCH', $path);
    }

    public function delete($path): Route
    {
        return $this->addRoute('DELETE', $path);
    }

    public function options($path)
    {
        return $this->addRoute('OPTIONS', $path);
    }
}
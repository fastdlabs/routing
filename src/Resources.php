<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Routing;


trait Resources
{
    public function get($path, $callback)
    {
        return $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        return $this->addRoute('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        return $this->addRoute('PUT', $path, $callback);
    }

    public function patch($path, $callback)
    {
        return $this->addRoute('PATCH', $path, $callback);
    }

    public function delete($path, $callback)
    {
        return $this->addRoute('DELETE', $path, $callback);
    }

    public function options($path, $callback)
    {
        return $this->addRoute('OPTIONS', $path, $callback);
    }
}
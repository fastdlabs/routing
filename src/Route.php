<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

/**
 * Class Route
 *
 * @package FastD\Routing
 */
class Route extends RouteRegex
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string|array
     */
    protected $method = 'ANY';

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    protected $name;

    /**
     * Route constructor.
     *
     * @param null $path
     * @param $callback
     * @param null $name
     * @param string $method
     * @param array $defaults
     */
    public function __construct($path, $callback, $name = null, $method = 'ANY', array $defaults = [])
    {
        parent::__construct($path);

        $name = empty($name) ? $path : $name;

        $this->setName($name);

        $this->setMethod($method);

        $this->setCallback($callback);

        $this->setParameters($defaults);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array|string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $callback
     * @return $this
     */
    public function setCallback($callback = null)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function mergeParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, array_filter($parameters));

        return $this;
    }
}

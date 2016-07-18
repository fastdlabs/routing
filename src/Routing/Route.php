<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 上午11:46
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Routing;

/**
 * Class Route
 *
 * @package FastD\Routing
 */
class Route
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * @var array
     */
    protected $requirements = [];

    /**
     * @var array
     */
    protected $arguments = [];

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
    protected $pathRegex;

    /**
     * Route constructor.
     * @param $method
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     */
    public function __construct($method, $path, $callback, array $defaults = [], array $requirements = [])
    {
        $this->setDefaults($defaults);
        $this->setRequirements($requirements);
        $this->setMethod($method);
        $this->setPath($path);
        $this->setCallback($callback);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        $this->pathRegex = $this->parsePathRegex();

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param array $defaults
     * @return $this
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param array|string $requirements
     * @return $this
     */
    public function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @return string
     */
    public function parsePathRegex()
    {
        $route = $this->path;

        if (preg_match_all('/\{(\w+)\}/i', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = '?P<' . $val . '>' . (isset($this->requirements[$val]) ? $this->requirements[$val] : '\w*');
                $default = null;
                $limited = '{1}';
                if (array_key_exists($val, $this->getDefaults())) {
                    $default = $this->defaults[$val];
                    $limited = '{0,1}';
                }
                $this->parameters[$val] = $default;
                $route = str_replace('{' . $val . '}', $limited . '(' . $pattern . ')', $route);
            }
        }

        if ('/' === substr($route, -1, 1)) {
            $route .= '{0,1}';
        }

        return '/^'.str_replace('/', '\/', $route).'$/';
    }


    /**
     * @return string
     */
    public function getPathRegex()
    {
        return $this->pathRegex;
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
}

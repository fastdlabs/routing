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

namespace Dobee\Routing;

/**
 * Class Route
 *
 * @package Dobee\Routing
 */
class Route implements RouteInterface
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $group;

    /**
     * @var string|array
     */
    protected $format = array('php');

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var array
     */
    protected $requirements = array();

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string|array
     */
    protected $method = array('ANY');

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    private $pathRegex;

    /**
     * @param string $route
     * @param string $name
     * @param array  $defaults
     * @param array  $method
     * @param array  $requirements
     * @param array  $format
     * @param null   $callback
     */
    public function __construct(
        $route,
        $name,
        array $defaults     = array(),
        array $method       = array('ANY'),
        array $requirements = array(),
        array $format       = array('php'),
        $callback           = null
    )
    {
        $this->route        = $route;

        $this->name         = $name;

        $this->defaults     = $defaults;

        $this->method       = $method;

        $this->requirements = $requirements;

        $this->format       = $format;

        $this->callback     = $callback;

        $this->parsePathRegex($route, $requirements);
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @param string $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
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
    public function setMethod(array $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
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
     * @param array $format
     * @return $this
     */
    public function setFormat(array $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $route
     * @param array  $requirements
     * @return $this
     */
    protected function parsePathRegex($route, $requirements = array())
    {
        if (preg_match_all('/\{(\w+)\}/i', $route, $match)) {

            foreach ($match[1] as $val) {
                $pattern = isset($requirements[$val]) ? $requirements[$val] : '?P<' . $val . '>.+';
                $route = str_replace('{' . $val . '}', '{1}(' . $pattern . ')', $route);
            }

            $this->arguments = $match[1];
        }

        $this->pathRegex = '/^' . str_replace('/', '\/', $route) . '$/';

        return $this;
    }


    /**
     * @return string
     */
    public function getPathRegex()
    {
        return $this->pathRegex;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
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
     * @return string
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
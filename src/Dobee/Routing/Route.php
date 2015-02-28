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
    protected $prefix = '';

    /**
     * @var array|string
     */
    protected $method = array();

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var array|string
     */
    protected $requirements = array();

    /**
     * @var string
     */
    protected $format = '';

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @var \Closures
     */
    protected $_callable = null;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param        $route
     * @param string $name
     * @param string $prefix
     * @param array  $parameters
     * @param array  $method
     * @param array  $defaults
     * @param array  $requirements
     * @param string $format
     */
    public function __construct($route = '', $name = "", $prefix = '', $parameters = array(), $method = array(), $defaults = array(), $requirements = array(), $format = "")
    {
        $this->setRoute(str_replace('//', '/', $route));

        $this->setName($name);

        $this->setPrefix($prefix);

        $this->setParameters($parameters);

        $this->setMethod($method);

        $this->setDefaults($defaults);

        $this->setRequirements($requirements);

        $this->setFormat($format);
    }

    /**
     * @param string $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        if (null !== $this->requirements) {
            $this->parsePattern($this->getRoute(), $this->getRequirements(), $this->getDefaults());
        }

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
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
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
    public function setDefaults($defaults)
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
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;

        if (null !== $this->route) {
            $this->parsePattern($this->getRoute(), $this->getRequirements(), $this->getDefaults());
        }

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
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
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
     * @param      $route
     * @param null $requirements
     * @return $this
     */
    protected function parsePattern($route, $requirements = null)
    {
        if (preg_match_all('/\{(\w+)\}/ui', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = isset($requirements[$val]) ? $requirements[$val] : '\w+';
                $route = str_replace('{' . $val . '}', '{1}(' . $pattern . ')', $route);
            }

            $this->arguments = $match[1];
        }

        $this->pattern = '/^' . str_replace('/', '\/', $route) . '$/';

        return $this;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments($arguments)
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
     * @param string $callable
     * @return $this
     */
    public function setCallable($callable)
    {
        $this->_callable = $callable;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallable()
    {
        return $this->_callable;
    }

    /**
     * @param $parameters
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return Route
     */
    /*public static function createRoute()
    {
        return new Route();
    }*/
}
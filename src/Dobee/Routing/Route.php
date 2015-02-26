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
class Route extends RouteAbstract
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var array|string
     */
    private $method = array();

    /**
     * @var array
     */
    private $defaults = array();

    /**
     * @var array|string
     */
    private $requirements = array();

    /**
     * @var string
     */
    private $format = '';

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var array
     */
    private $arguments = array();

    /**
     * @var string
     */
    private $_controller;

    /**
     * @var array
     */
    private $_parameters = array();

    /**
     * @param        $route
     * @param string $name
     * @param string $prefix
     * @param string $controller
     * @param array  $parameters
     * @param array  $method
     * @param array  $defaults
     * @param array  $requirements
     * @param string $format
     */
    public function __construct($route, $name = "", $prefix = '', $controller = '', $parameters = array(), $method = array(), $defaults = array(), $requirements = array(), $format = "")
    {
        $this->route = str_replace('//', '/', $route);

        $this->name = $name;

        $this->prefix = $prefix;

        $this->_controller = $controller;

        $this->_parameters = $parameters;

        $this->method = $method;

        $this->defaults = $defaults;

        $this->requirements = $requirements;

        $this->format = $format;

        $this->parsePattern($this->route, $this->requirements, $this->defaults);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return array
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
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
     * @param null $defaults
     * @return $this
     */
    protected function parsePattern($route, $requirements = null, $defaults = null)
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
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @param $parameters
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->_parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }
}
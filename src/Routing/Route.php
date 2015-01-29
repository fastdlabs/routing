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

class Route extends RouteAbstract
{
    private $route;

    private $name;

    private $method = array();

    private $defaults = array();

    private $requirements = array();

    private $format = array('php', 'html', 'json', 'xml');

    private $pattern;

    private $arguments;

    private $_controller;

    private $_parameters = array();

    public function __construct($route, $name = "", $controller = '', $parameters = array(), $method = array(), $defaults = array(), $requirements = array(), $format = "php")
    {
        $this->route = str_replace('//', '/', $route);

        $this->name = $name;

        $this->_controller = $controller;

        $this->_parameters = $parameters;

        $this->method = $method;

        $this->defaults = $defaults;

        $this->requirements = $requirements;

        $this->format = $format;

        $this->parsePattern($this->route, $this->requirements, $this->defaults);
    }

    /**
     * @return mixed
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

    protected function parsePattern($route, $requirements = null, $defaults = null)
    {
        if (preg_match_all('/\{(\w+)\}/ui', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = isset($requirements[$val]) ? $requirements[$val] : '\w+';
                $route = str_replace('{' . $val . '}', '?(' . $pattern . ')', $route);
            }

            $this->arguments = $match[1];
        }

        $this->pattern = '/^' . str_replace('/', '\/', $route) . '$/';

        return $this;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function getParameters()
    {
        return $this->_parameters;
    }
}
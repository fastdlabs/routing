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
    protected $group;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|array
     */
    protected $format;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var array
     */
    protected $requirements;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var string|array
     */
    protected $method;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    private $pathRegex;

    /**
     * @param array $routeBag
     */
    public function __construct(array $routeBag)
    {
        $this->class        = $routeBag['class'];
        $this->action       = $routeBag['action'];
        $this->route        = $routeBag['route'];
        $this->name         = $routeBag['name'];
        $this->group        = $routeBag['group'];
        $this->method       = $routeBag['method'];
        $this->defaults     = $routeBag['defaults'];
        $this->requirements = $routeBag['requirements'];
        $this->format       = $routeBag['format'];
        $this->arguments    = $routeBag['arguments'];
        $this->parameters   = $routeBag['parameters'];

        $this->parsePathRegex($this->route, $this->requirements);
    }

    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup()
    {
        return $this->group;
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
     * @param string $route
     * @param array  $requirements
     * @return $this
     */
    protected function parsePathRegex($route, $requirements = array())
    {
        if (preg_match_all('/\{(\w+)\}/ui', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = isset($requirements[$val]) ? $requirements[$val] : '.*?';
                $route = str_replace('{' . $val . '}', '{1}(' . $pattern . ')', $route);
            }

            $this->setArguments($match[1]);
        }

        $this->setPathRegex('/^' . str_replace('/', '\/', $this->getGroup() . $route) . '$/');

        return $this;
    }

    /**
     * @param string $regex
     * @return $this
     */
    public function setPathRegex($regex)
    {
        $this->pathRegex = $regex;

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
    public function setCallback($callback)
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
     * @param $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
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
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }
}
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

use Dobee\Routing\Rest\RESTRouteSetting;

/**
 * Class Route
 *
 * @package Dobee\Routing
 */
class Route implements RouteInterface, RESTRouteSetting
{
    /**
     * @var RouteParameterBagInterface
     */
    protected $routeParametersBag;

    /**
     * @param RouteParameterBagInterface $routeParametersBag
     */
    public function __construct(RouteParameterBagInterface $routeParametersBag = null)
    {
        $this->routeParametersBag = $routeParametersBag;

        $this->parsePattern($routeParametersBag->getRoute(), $routeParametersBag->getRequirements());
    }

    /**
     * @param string $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->routeParametersBag->setRoute($route);

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->routeParametersBag->getRoute();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->routeParametersBag->setName($name);

        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->routeParametersBag->getName();
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->routeParametersBag->setPrefix($prefix);

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->routeParametersBag->getPrefix();
    }

    /**
     * @param array|string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->routeParametersBag->setMethod($method);

        return $this;
    }

    /**
     * @return array
     */
    public function getMethod()
    {
        return $this->routeParametersBag->getMethod();
    }

    /**
     * @param array $defaults
     * @return $this
     */
    public function setDefaults(array $defaults)
    {
        $this->routeParametersBag->setDefaults($defaults);

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->routeParametersBag->getDefaults();
    }

    /**
     * @param array|string $requirements
     * @return $this
     */
    public function setRequirements(array $requirements)
    {
        $this->routeParametersBag->setRequirements($requirements);

        return $this;
    }

    /**
     * @return array
     */
    public function getRequirements()
    {
        return $this->routeParametersBag->getRequirements();
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->routeParametersBag->setFormat($format);

        return $this;
    }

    /**
     * @return array|string
     */
    public function getFormat()
    {
        return $this->routeParametersBag->getFormat();
    }

    /**
     * @param string $route
     * @param array  $requirements
     * @return $this
     */
    protected function parsePattern($route, $requirements = array())
    {
        if (preg_match_all('/\{(\w+)\}/ui', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = isset($requirements[$val]) ? $requirements[$val] : '\w+';
                $route = str_replace('{' . $val . '}', '{1}(' . $pattern . ')', $route);
            }

            $this->setArguments($match[1]);
        }

        $this->setPattern('/^' . str_replace('/', '\/', $route) . '$/');

        return $this;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->routeParametersBag->setPattern($pattern);

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->routeParametersBag->getPattern();
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments($arguments)
    {
        $this->routeParametersBag->setArguments($arguments);

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->routeParametersBag->getArguments();
    }

    /**
     * @param string $callable
     * @return $this
     */
    public function setCallable($callable)
    {
        $this->routeParametersBag->setCallable($callable);

        return $this;
    }

    /**
     * @return string
     */
    public function getCallable()
    {
        return $this->routeParametersBag->getCallable();
    }

    /**
     * @param $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->routeParametersBag->setParameters($parameters);

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->routeParametersBag->getParameters();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->routeParametersBag->getClass();
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->routeParametersBag->setClass($class);

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->routeParametersBag->getAction();
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->routeParametersBag->setAction($action);

        return $this;
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function get($setting, $callback)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function post($setting, $callback)
    {
        // TODO: Implement post() method.
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function put($setting, $callback)
    {
        // TODO: Implement put() method.
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function delete($setting, $callback)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function options($setting, $callback)
    {
        // TODO: Implement options() method.
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function head($setting, $callback)
    {
        // TODO: Implement head() method.
    }

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function any($setting, $callback)
    {
        // TODO: Implement any() method.
    }

    public static function createRoute($method, $setting, $callable)
    {
        if (!method_exists(self, $method)) {
            throw new \BadMethodCallException(sprintf('Route method "%s" is not exists.', $method));
        }

        return (new self)->$method($setting, $callable);
    }

    public static function __callStatic($method, $arguments = array())
    {
        return call_user_func_array(array((new self), $method), $arguments);
    }
}
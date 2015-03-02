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

<<<<<<< HEAD
use Dobee\Routing\RouteParameterBagInterface;
=======
use Dobee\Routing\Rest\RESTRouteSetting;
>>>>>>> master

/**
 * Class Route
 *
 * @package Dobee\Routing
 */
<<<<<<< HEAD
class Route implements RouteInterface
=======
class Route implements RouteInterface, RESTRouteSetting
>>>>>>> master
{
    /**
     * @var RouteParameterBagInterface
     */
<<<<<<< HEAD
    protected $route;
=======
    protected $routeParametersBag;
>>>>>>> master

    /**
     * @param RouteParameterBagInterface $routeParametersBag
     */
<<<<<<< HEAD
    protected $name;

    /**
     * @var string
     */
    protected $prefix = '';
=======
    public function __construct(RouteParameterBagInterface $routeParametersBag = null)
    {
        $this->routeParametersBag = $routeParametersBag;

        $this->parsePattern($routeParametersBag->getRoute(), $routeParametersBag->getRequirements());
    }
>>>>>>> master

    /**
     * @param string $route
     * @return $this
     */
<<<<<<< HEAD
    protected $method = array();

    /**
     * @var array
     */
    protected $defaults = array();
=======
    public function setRoute($route)
    {
        $this->routeParametersBag->setRoute($route);

        return $this;
    }
>>>>>>> master

    /**
     * @return string
     */
<<<<<<< HEAD
    protected $requirements = array();
=======
    public function getRoute()
    {
        return $this->routeParametersBag->getRoute();
    }
>>>>>>> master

    /**
     * @param string $name
     * @return $this
     */
<<<<<<< HEAD
    protected $format = '';
=======
    public function setName($name)
    {
        $this->routeParametersBag->setName($name);
>>>>>>> master

        return $this;
    }
    /**
     * @return string
     */
<<<<<<< HEAD
    protected $pattern;
=======
    public function getName()
    {
        return $this->routeParametersBag->getName();
    }
>>>>>>> master

    /**
     * @param string $prefix
     * @return $this
     */
<<<<<<< HEAD
    protected $arguments = array();

    /**
     * @var \Closures
     */
    protected $_callable = null;
=======
    public function setPrefix($prefix)
    {
        $this->routeParametersBag->setPrefix($prefix);

        return $this;
    }
>>>>>>> master

    /**
     * @return string
     */
<<<<<<< HEAD
    protected $parameters = array();

    /**
     * @var string
     */
    protected $class = "";

    /**
     * @var string
     */
    protected $action = "";

    /**
     * @var RouteParameterBagInterface
     */
    protected $routeParameters;

    /**
     * @param RouteParameterBagInterface $routeBag
     */
    public function __construct(RouteParameterBagInterface $routeBag)
    {
        $this->routeParameters = $routeBag;
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

=======
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

>>>>>>> master
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
<<<<<<< HEAD
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
=======
     * @param array $defaults
     * @return $this
>>>>>>> master
     */
    public function setDefaults(array $defaults)
    {
        $this->routeParametersBag->setDefaults($defaults);

        return $this;
    }

    /**
<<<<<<< HEAD
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
=======
     * @return array
>>>>>>> master
     */
    public function getDefaults()
    {
        return $this->routeParametersBag->getDefaults();
    }

    /**
<<<<<<< HEAD
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
=======
     * @param array|string $requirements
     * @return $this
>>>>>>> master
     */
    public function setRequirements(array $requirements)
    {
        $this->routeParametersBag->setRequirements($requirements);

        return $this;
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
    public function getRequirements()
    {
        return $this->routeParametersBag->getRequirements();
    }

    /**
<<<<<<< HEAD
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
=======
     * @param string $format
     * @return $this
>>>>>>> master
     */
    public function setFormat($format)
    {
        $this->routeParametersBag->setFormat($format);

        return $this;
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
        return $this->routeParametersBag->getFormat();
    }

    /**
<<<<<<< HEAD
     * @param      $route
     * @param null $requirements
     * @return $this
     */
    protected function parsePattern($route, $requirements = null)
=======
     * @param string $route
     * @param array  $requirements
     * @return $this
     */
    protected function parsePattern($route, $requirements = array())
>>>>>>> master
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
<<<<<<< HEAD
        return $this->_callable;
=======
        return $this->routeParametersBag->getCallable();
>>>>>>> master
    }

    /**
     * @param $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
<<<<<<< HEAD
        $this->parameters = $parameters;
=======
        $this->routeParametersBag->setParameters($parameters);
>>>>>>> master

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
<<<<<<< HEAD
        return $this->parameters;
=======
        return $this->routeParametersBag->getParameters();
>>>>>>> master
    }

    /**
     * @return string
     */
    public function getClass()
    {
<<<<<<< HEAD
        return $this->class;
=======
        return $this->routeParametersBag->getClass();
>>>>>>> master
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
<<<<<<< HEAD
        $this->class = $class;
=======
        $this->routeParametersBag->setClass($class);
>>>>>>> master

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
<<<<<<< HEAD
        return $this->action;
=======
        return $this->routeParametersBag->getAction();
>>>>>>> master
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
<<<<<<< HEAD
        $this->action = $action;

        return $this;
=======
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
>>>>>>> master
    }
}
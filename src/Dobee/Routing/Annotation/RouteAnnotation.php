<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 上午9:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing\Annotation;

use Dobee\Annotation\RulesAbstract;
use Dobee\Routing\RouteBag;

/**
 * Class RouteAnnotation
 *
 * @package Dobee\Routing\Annotation
 */
class RouteAnnotation extends RulesAbstract implements RouteAnnotationInterface
{
    /**
     * @var string
     */
    private $prefix = null;

<<<<<<< HEAD
    private $annotation = array();
=======
    private $class = null;
>>>>>>> master

    /**
     * Parse PHP class annotation.
     *
<<<<<<< HEAD
     * @param \ReflectionClass $reflectionClass
     * @return array
     */
    public function parserAnnotation(\ReflectionClass $reflectionClass)
    {
        $parameters = array(
            'namespace'     => $reflectionClass->getNamespaceName(),
            'class'         => $reflectionClass->getName(),
            'path'          => dirname($reflectionClass->getFileName()),
            'route_prefix'  => $this->getAnnotationClassPrefix($reflectionClass->getDocComment()),
        );

        foreach ($reflectionClass->getMethods() as $method) {
            if (!$this->hasRouteAnnotation($method)) {
                continue;
            }

            $routeParameters = $this->getRouteParameters($method);
            print_r($routeParameters);
            die;
            $routeParameters['class'] = $parameters['class'];
            $routeParameters['action'] = $method->getName();
            $routeParameters['parameters'] = $this->getMethodParameters($method);
            $parameters['action'][isset($routeParameters['name']) ? $routeParameters['name'] : ""] = $routeParameters;
        }
        print_r($parameters);die;
        return $parameters;
    }

    /**
     * @param $annotation
     * @return string|null
     */
    public function getAnnotationClassPrefix($annotation)
    {
        if (null === $this->prefix) {
            preg_match('/\@Route\(\"?(.*?)\"?\)/', $annotation, $prefix);
=======
     * @param \ReflectionClass $class
     * @return array
     */
    public function parserAnnotation(\ReflectionClass $class)
    {
        $routes = array();

        foreach ($class->getMethods() as $method) {
            if (!$this->hasRouteAnnotation($method)) {
                continue;
            }

            $routeParametersBag = $this->getRouteParameters($method);
            $routes[$routeParametersBag->getName()] = $routeParametersBag;
        }

        return $routes;
    }

    /**
     * @param \ReflectionClass $class
     * @return string|null
     */
    public function getRoutePrefix(\ReflectionClass $class)
    {
        if (null === $this->prefix || $class->getName() !== $this->class) {
            preg_match('/\@Route\(\"?(.*?)\"?\)/', $class->getDocComment(), $prefix);
>>>>>>> master
            $this->prefix = (!empty($prefix) && isset($prefix[1])) ? $prefix[1] : '';
            $this->class = $class->getName();
        }

        return $this->prefix;
    }

    /**
<<<<<<< HEAD
     * @param $annotation
     * @return array
     */
    public function getAnnotationMethodParameters($annotation)
=======
     * @param \ReflectionMethod $method
     * @return RouteBag|null
     */
    public function getRouteParameters(\ReflectionMethod $method)
    {
        if ($this->hasRouteAnnotation($method)) {
            return $this->parseRouteParameters($method);
        }

        return null;
    }

    /**
     * @param \ReflectionMethod $method
     * @return RouteBag
     */
    public function parseRouteParameters(\ReflectionMethod $method)
>>>>>>> master
    {
        preg_match_all('/\@Route\((.*?)\)/', str_replace(array("\r\n", "\n", '*'), '', $method->getDocComment()), $match);

        $match = implode(',', $match[1]);

        $annotation = preg_replace(array('/\*{1,}/', '/\s{1,}/'), array('', ''), $match);

        $annotation = explode(PHP_EOL, preg_replace('/\,(\w+)/', PHP_EOL . '$1', $annotation));

<<<<<<< HEAD
        $parameters = new \ArrayObject(array(
            'prefix'        => $this->prefix,
=======
        $parameters = array(
            'prefix'        => $this->getRoutePrefix($method->getDeclaringClass()),
>>>>>>> master
            'route'         => trim(array_shift($annotation), '"'),
            'name'          => '',
            'method'        => 'ANY',
            'defaults'      => '',
<<<<<<< HEAD
            'requirements'  => '',
            'format'        => '',
            'parameters'    => array(),
            'class'         => '',
            'action'        => '',
        ));
=======
            'requirements'  => array(),
            'arguments'     => array(),
            'parameters'    => array(),
            'format'        => '',
            'class'         => $method->getDeclaringClass()->getName(),
            'action'        => $method->getName(),
            'path'          => $method->getDeclaringClass()->getFileName()
        );
>>>>>>> master

        foreach ($annotation as $val) {
            list($key, $value) = explode("=", $val);
            $value = str_replace('\\', '\\\\', trim($value, '"'));
            $value = ($json = json_decode($value, true)) ? $json : $value;
            $parameters[$key] = $value;
        }

<<<<<<< HEAD
        return $parameters;
    }

    /**
     * @param $annotation
     * @return array|null
     */
    public function getAnnotationMethod($annotation)
    {
        if (false !== strstr($annotation, '@Route')) {
            preg_match_all('/\@Route\((.*?)\)/', str_replace(array("\r\n", "\n", '*'), '', $annotation), $match);
            $match = implode(',', $match[1]);
            $match = preg_replace(array('/\*{1,}/', '/\s{1,}/'), array('', ''), $match);
            return $this->getAnnotationMethodParameters($match);
        }

        return null;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getMethodParameters(\ReflectionMethod $reflectionMethod)
    {
        $parameters = array();

        foreach ($reflectionMethod->getParameters() as $val) {
            if (!is_object($val->getClass())) {
                $parameters[] = $val->getName();
            }
        }

        unset($reflectionMethod);

        return $parameters;
    }

    public function getRouteParameters(\ReflectionMethod $method)
    {

    }

    public function getRouteName(\ReflectionMethod $method)
    {

    }

=======
        return new RouteBag($parameters);
    }

>>>>>>> master
    /**
     * @param \ReflectionMethod $method
     * @return bool
     */
    public function hasRouteAnnotation(\ReflectionMethod $method)
<<<<<<< HEAD
    {
        return false !== strpos($method->getDocComment(), '@Route');
    }

    /**
     * @param $annotation
     * @return bool
     */
    public function hasAnnotation($annotation)
    {
        // TODO: Implement hasAnnotation() method.
=======
    {
        return false !== strpos($method->getDocComment(), '@Route');
>>>>>>> master
    }
}
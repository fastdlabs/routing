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

    private $annotation = array();

    /**
     * Parse PHP class annotation.
     *
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
            $this->prefix = (!empty($prefix) && isset($prefix[1])) ? $prefix[1] : '';
        }

        return $this->prefix;
    }

    /**
     * @param $annotation
     * @return array
     */
    public function getAnnotationMethodParameters($annotation)
    {
        $annotation = explode(PHP_EOL, preg_replace('/\,(\w+)/', PHP_EOL . '$1', $annotation));

        $parameters = new \ArrayObject(array(
            'prefix'        => $this->prefix,
            'route'         => trim(array_shift($annotation), '"'),
            'name'          => '',
            'method'        => 'ANY',
            'defaults'      => '',
            'requirements'  => '',
            'format'        => '',
            'parameters'    => array(),
            'class'         => '',
            'action'        => '',
        ));

        foreach ($annotation as $val) {
            list($key, $value) = explode("=", $val);
            $value = str_replace('\\', '\\\\', trim($value, '"'));
            $value = ($json = json_decode($value, true)) ? $json : $value;
            $parameters[$key] = $value;
        }

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

    /**
     * @param \ReflectionMethod $method
     * @return bool
     */
    public function hasRouteAnnotation(\ReflectionMethod $method)
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
    }
}
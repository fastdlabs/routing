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
class RouteAnnotation extends RulesAbstract
{
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @param \ReflectionClass $reflectionClass
     * @return array
     */
    public function parserAnnotation(\ReflectionClass $reflectionClass)
    {
        $parameters = array();

        $this->getAnnotationPrefix($reflectionClass->getDocComment());

        foreach ($reflectionClass->getMethods() as $val) {
            if (!$this->hasAnnotation($val->getDocComment())) {
                continue;
            }
            $routeParameters = $this->getAnnotationMethod($val->getDocComment());
            $routeParameters['_controller'] = $val->getNamespaceName() . $val->class . '@' . $val->getName();
            $routeParameters['_parameters'] = $this->getMethodParameters($val);
            $parameters[isset($routeParameters['name']) ? $routeParameters['name'] : ""] = $routeParameters;
        }

        return $parameters;
    }

    /**
     * @param $annotation
     * @return string
     */
    public function getAnnotationPrefix($annotation)
    {
        if (empty($this->prefix)) {
            preg_match('/\@Route\(\"?(.*?)\"?\)/', $annotation, $prefix);
            $this->prefix = (!empty($prefix) && isset($prefix[1])) ? $prefix[1] : '';
        }

        return $this->prefix;
    }

    /**
     * @param $annotation
     * @return array
     */
    public function getAnnotationParameters($annotation)
    {
        $annotation = explode(PHP_EOL, preg_replace('/\,(\w+)/', PHP_EOL . '$1', $annotation));

        $parameters = array(
            'prefix' => $this->prefix,
            'route' => str_replace('//', '/', $this->prefix . trim(array_shift($annotation), '"')),
            'name' => '',
            'method' => '',
            'defaults' => '',
            'requirements' => '',
            'format' => '',
        );

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
            $match = preg_replace(['/\*{1,}/', '/\s{1,}/'], ['', ''], $match);
            return $this->getAnnotationParameters($match);
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

    /**
     * @param $annotation
     * @return bool
     */
    public function hasAnnotation($annotation)
    {
        return false !== strpos($annotation, '@Route');
    }
}
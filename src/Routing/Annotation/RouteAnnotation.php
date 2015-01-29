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

class RouteAnnotation extends RulesAbstract
{
    private $prefix = '';

    public function parserAnnotation(\ReflectionClass $reflectionClass)
    {
        $parameters = array();

        $this->getAnnotationPrefix($reflectionClass->getDocComment());

        foreach ($reflectionClass->getMethods() as $val) {
            $routeParameters = $this->getAnnotationMethod($val->getDocComment());
            $routeParameters['_controller'] = $val->getNamespaceName() . $val->class . '@' . $val->getName();
            $routeParameters['_parameters'] = $this->getMethodParameters($val);
            $parameters[isset($routeParameters['name']) ? $routeParameters['name'] : ""] = $routeParameters;
        }

        return $parameters;
    }

    public function getAnnotationPrefix($annotation)
    {
        if (empty($this->prefix)) {
            preg_match('/\@Route\(\"?(.*?)\"?\)/', $annotation, $prefix);
            $this->prefix = (!empty($prefix) && isset($prefix[1])) ? $prefix[1] : '';
        }

        return $this->prefix;
    }

    public function getAnnotationParameters($annotation)
    {
        $annotation = explode(PHP_EOL, preg_replace('/\,(\w+)/', PHP_EOL . '$1', $annotation));

        $parameters = array(
            'prefix' => $this->prefix,
            'route' => $this->prefix . trim(array_shift($annotation), '"'),
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
}
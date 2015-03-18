<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午4:56
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Annotation;

use Dobee\Routing\RouteCollectionInterface;
use Dobee\Routing\Route;

/**
 * Parse route annotation. Extract route variable.
 *
 * Class AnnotationContext
 *
 * @package Dobee\Routing\Annotation
 */
class RouteAnnotationContext
{
    /**
     * @var array
     */
    private $parameters = array(
        'prefix'        => '',
        'route'         => '',
        'name'          => '',
        'method'        => 'ANY',
        'defaults'      => '',
        'requirements'  => array(),
        'arguments'     => array(),
        'parameters'    => array(),
        'format'        => '',
        'class'         => '',
        'action'        => '',
    );

    /**
     * @var RouteCollectionInterface
     */
    protected $collections;

    /**
     * @param RouteCollectionInterface $routeCollectionInterface
     */
    public function __construct(RouteCollectionInterface $routeCollectionInterface)
    {
        $this->collections = $routeCollectionInterface;
    }

    /**
     * @param null   $group
     * @param string $class
     * @return RouteCollectionInterface
     */
    public function getRoutes($group = null, $class = '')
    {
        $reflection = new \ReflectionClass($class);

        $group = (empty($group) ? '' : $group) . $this->parseAnnotation($reflection->getDocComment())['route'];

        foreach ($reflection->getMethods() as $method) {
            $route = new Route(array_merge(
                $this->parseAnnotation($method->getDocComment()),
                array(
                    'class'     => $reflection->getName(),
                    'action'    => $method->getName(),
                    'group'     => $group,
                )
            ));

            $this->collections->setRoute($route);
        }

        unset($reflection);

        return $this->collections;
    }

    /**
     * @param string $annotation The defined route annotation.
     * @return array
     */
    public function parseAnnotation($annotation)
    {
        preg_match_all('/\@Route\((.*?)\)/', str_replace(array("\r\n", "\n", '*'), '', $annotation), $match);

        $match = implode(',', $match[1]);

        $annotation = preg_replace(array('/\*{1,}/', '/\s{1,}/'), array('', ''), $match);

        $annotation = explode(PHP_EOL, preg_replace('/\,(\w+)/', PHP_EOL . '$1', $annotation));

        $parameters = array(
            'route'         => trim(array_shift($annotation), '"'),
            'name'          => '',
            'method'        => 'ANY',
            'defaults'      => '',
            'requirements'  => array(),
            'arguments'     => array(),
            'parameters'    => array(),
            'format'        => '',
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
     * @return bool
     */
    public function hasRouteAnnotation($annotation)
    {
        return false !== strpos($annotation, '@Route');
    }
}
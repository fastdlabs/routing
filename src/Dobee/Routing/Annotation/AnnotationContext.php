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

use Dobee\Routing\RouteBag;

/**
 * 路由参数上下文，整理过滤路由构造参数，创建路由实体。
 *
 * Class AnnotationContext
 *
 * @package Dobee\Routing\Annotation
 */
class AnnotationContext
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
     * @param array $parameters
     * @return RouteBag
     */
    public function getRouteParametersBag($parameters = array())
    {
        if (!isset($parameters['Route'])) {
            return false;
        }

        $this->parameters['route'] = $parameters['Route']['parameters'][0];
        unset($parameters['Route']['parameters'][0]);
        $this->parameters = array_merge($this->parameters, $parameters['Route']['parameters']);
        $this->parameters['parameters'] = $parameters['Route']['mapped']['parameters'];
        $this->parameters['class'] = $parameters['Route']['mapped']['class'];
        $this->parameters['action'] = $parameters['Route']['mapped']['method'];

        return new RouteBag($this->parameters);
    }
}
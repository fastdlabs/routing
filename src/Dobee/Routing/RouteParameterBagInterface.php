<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午4:42
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing;

/**
 * Interface RouteParameterBagInterface
 *
 * @package Dobee\Routing
 */
interface RouteParameterBagInterface
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getAction();

    /**
     * @return string
     */
    public function getRoute();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @return array
     */
    public function getRequirements();

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return array
     */
    public function getParameters();
}
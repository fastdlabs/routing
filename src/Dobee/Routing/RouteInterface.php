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
 * Interface RouteInterface
 *
 * @package Dobee\Routing
 */
interface RouteInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string|array
     */
    public function getArguments();

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @return string
     */
    public function getRoute();

    /**
     * @return string
     */
    public function getPattern();

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @return string|array
     */
    public function getRequirements();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return string|array
     */
    public function getMethod();

    /**
     * @return mixed
     */
    public function getCallable();
}
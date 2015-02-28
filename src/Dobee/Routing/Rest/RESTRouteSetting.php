<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午3:16
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Rest;

/**
 * Interface RESTRouteSetting
 *
 * Set RESTFul Routing in simple api framework.
 *
 * @package Dobee\Routing
 */
interface RESTRouteSetting
{
    /**
     * @return $this
     */
    public function get();

    /**
     * @return $this
     */
    public function post();

    /**
     * @return $this
     */
    public function put();

    /**
     * @return $this
     */
    public function delete();

    /**
     * @return $this
     */
    public function options();

    /**
     * @return $this
     */
    public function head();

    /**
     * @return $this
     */
    public function any();

    /**
     * @return void
     */
    public function run();
}
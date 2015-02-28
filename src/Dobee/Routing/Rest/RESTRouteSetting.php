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
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function get($setting, $callback);

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function post($setting, $callback);

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function put($setting, $callback);

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function delete($setting, $callback);

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function options($setting, $callback);

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function head($setting, $callback);

    /**
     * @param $setting
     * @param $callback
     * @return $this
     */
    public function any($setting, $callback);

    /**
     * @return void
     */
    public function run();
}
<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/6
 * Time: 下午11:37
 */

namespace Dobee\Component\Routing\Collections;

use Dobee\Component\Routing\Route\RouteInterface;

/**
 * Interface RouteCollectionInterface
 *
 * @package Dobee\Component\Routing\Collections
 */
interface RouteCollectionInterface
{
    /**
     * @param                $name
     * @param RouteInterface $routeInterface
     * @return mixed
     */
    public function add($name, RouteInterface $routeInterface);

    /**
     * @return mixed
     */
    public function getRoutes();
}
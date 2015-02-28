<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/6
 * Time: 下午11:37
 */

namespace Dobee\Routing\Collections;

use Dobee\Routing\RouteInterface;
use Dobee\Routing\RouteInvalidException;
use Dobee\Routing\RouteNotFoundException;

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
     * @return $this
     * @throws RouteInvalidException
     * @throws \Exception
     */
    public function setRoute($name, RouteInterface $routeInterface = null);

    /**
     * @param $name
     * @return RouteInterface
     * @throws RouteNotFoundException
     */
    public function getRoute($name);

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function hasRoute($name);

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function removeRoute($name);

    /**
     * @return mixed
     */
    public function getRouteCollections();
}
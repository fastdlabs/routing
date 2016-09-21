<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

/**
 * Class RouteCache
 *
 * @package FastD\Routing
 */
class RouteCache
{
    const CACHE = '.route.cache';

    protected $collection;

    protected $dir;

    public function __construct(RouteCollection $routeCollection, $dir = null)
    {
        $this->collection = $routeCollection;

        $this->dir = $this->targetDirectory($dir);
    }

    protected function targetDirectory($dir)
    {
        if (null === $dir) {
            return false;
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    public function dump()
    {
        print_r($this->collection->getStaticsMap());
    }
}
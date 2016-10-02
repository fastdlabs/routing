<?php
/**
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

    /**
     * @var RouteCollection
     */
    protected $collection;

    /**
     * @var bool
     */
    protected $dir;

    /**
     * @var string
     */
    protected $cache;

    /**
     * RouteCache constructor.
     *
     * @param RouteCollection $routeCollection
     * @param null $dir
     */
    public function __construct(RouteCollection $routeCollection, $dir = null)
    {
        $this->collection = $routeCollection;

        $this->dir = $this->targetDirectory($dir);

        $this->cache = $this->dir . DIRECTORY_SEPARATOR . static::CACHE;

        $this->loadCache();
    }

    /**
     * @param $dir
     * @return bool
     */
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

    /**
     * @return string
     */
    public function dump()
    {
        $statics = $this->dumpStatics();

        $dynamics = $this->dumpDynamics();

        return var_export([
            'statics' => $statics,
            'dynamics' => $dynamics,
        ], true);
    }

    /**
     * Dump static routes.
     *
     * @return array
     */
    protected function dumpStatics()
    {
        $cacheData = [];

        foreach ($this->collection->staticRoutes as $key => $list) {
            foreach ($list as $name => $route) {
                if (is_object($route)) {
                    $cacheData[$key][$route->getPath()] = [
                        'name' => $route->getName(),
                        'path' => $route->getPath(),
                        'method' => $route->getMethod(),
                        'variables' => $route->getVariables(),
                        'requirements' => $route->getRequirements(),
                        'defaults' => $route->getParameters(),
                        'regex' => $route->getRegex(),
                        'callback' => $route->getCallback(),
                    ];
                }
            }
        }

        return $cacheData;
    }

    /**
     * Dump dynamic routes.
     *
     * @return array
     */
    protected function dumpDynamics()
    {
        $cacheData = [];

        foreach ($this->collection->dynamicRoutes as $key => $list) {
            foreach ($list as $name => $routeChunk) {
                if (isset($routeChunk['regex'])) {
                    $cacheData[$key][$name]['regex'] = $routeChunk['regex'];
                }
                foreach ($routeChunk['routes'] as $index => $route) {
                    if (is_object($route)) {
                        $cacheData[$key][$name]['routes'][$index] = [
                            'name' => $route->getName(),
                            'path' => $route->getPath(),
                            'method' => $route->getMethod(),
                            'variables' => $route->getVariables(),
                            'requirements' => $route->getRequirements(),
                            'defaults' => $route->getParameters(),
                            'regex' => $route->getRegex(),
                            'callback' => $route->getCallback(),
                        ];
                    }
                }
            }
        }

        return $cacheData;
    }

    /**
     * @return string
     */
    protected function wrapPhp()
    {
        return '<?php return ' . $this->dump() . ';';
    }

    /**
     * Load routes cache.
     *
     * @return void
     */
    public function loadCache()
    {
        if (file_exists($this->cache)) {
            $cacheData = include $this->cache;
            $this->collection->map($cacheData);
        }
    }

    /**
     * @return void
     */
    public function saveCache()
    {
        file_put_contents($this->cache, $this->wrapPhp());
    }
}
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
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->collection = $routeCollection;

        $this->cache = $this->dir . DIRECTORY_SEPARATOR . static::CACHE;
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
        return var_export([
            'statics' => $this->dumpStatics(),
            'dynamics' => $this->dumpDynamics(),
            'alias' => $this->dumpAlias(),
        ], true);
    }

    /**
     * @return array
     */
    protected function dumpAlias()
    {
        $cacheData = [];

        foreach ($this->collection->aliasMap as $key => $list) {
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
     * @return bool
     */
    public function hasCache()
    {
        return file_exists($this->cache);
    }

    /**
     * @param $dir
     */
    public function loadCache($dir)
    {
        $this->dir = $this->targetDirectory($dir);

        if ($this->hasCache()) {
            $routes = include $this->cache;
            $this->collection->staticRoutes = isset($routes['statics']) ? $routes['statics'] : [];
            $this->collection->dynamicRoutes = isset($routes['dynamics']) ? $routes['dynamics'] : [];
            $this->collection->aliasMap = isset($routes['alias']) ? $routes['alias'] : [];
        }
    }

    /**
     * @param $dir
     */
    public function saveCache($dir)
    {
        $this->dir = $this->targetDirectory($dir);

        file_put_contents($this->cache, $this->wrapPhp());
    }
}
<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

use FastD\Generator\Factory\Method;
use FastD\Generator\Factory\Param;
use FastD\Generator\Generator;

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
     * @var array
     */
    protected $includes = [];

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
        $statics = $this->collection->getStaticsMap();
        $dynamics = $this->collection->getDynamicsMap();

        foreach ($this->includes as $include) {
            if (file_exists($include)) {
                unlink($include);
            }
        }

        $self = $this;
        $cacheData = [];
        $includes = [];

        $dumpClosure = function ($callback) use (&$includes, $self) {
            if (is_callable($callback)) {
                $hash = 'route_closure_' . md5(mt_rand(0, 999999));
                $name = 'invoke';
                $obj = new Generator($hash);
                $method = new Method($name);
                $method->setParams([new Param('test')]);
                $method->setTodo('return "hello world";');
                $obj->setMethods([$method]);
                $cache = $self->dir . '/closures/' . $hash . '.php';
                $obj->save($cache);
                $includes[$hash] = $cache;
                return $hash . '@' . $name;
            } else if (is_array($callback)) {
                if (is_object($callback[0])) {
                    return get_class($callback[0]) . '@' . $callback[1];
                } else {
                    return $callback;
                }
            }
        };

        $dump = function ($type, array $routes) use (&$cacheData, $dumpClosure) {
            foreach ($routes as $key => $list) {
                foreach ($list as $name => $route) {
                    $callback = $route->getCallback();
                    $cacheData[$type][$key][] = [
                        'name' => $route->getName(),
                        'path' => $route->getPath(),
                        'method' => $route->getMethod(),
                        'variables' => $route->getVariables(),
                        'requirements' => $route->getRequirements(),
                        'regex' => $route->getRegex(),
                        'callback' => (is_callable($callback) || is_array($callback)) ? $dumpClosure($callback) : $callback,
                    ];
                }
            }
        };

        $dump('statics', $statics);
        $dump('dynamics', $dynamics);

        $cacheData['includes'] = $includes;

        return '<?php return ' . var_export($cacheData, true) . ';';
    }

    /**
     *
     */
    public function loadCache()
    {
        if (file_exists($this->cache)) {
            $cacheData = include $this->cache;
            $this->includes = isset($cacheData['includes']) ? $cacheData['includes'] : [];
            foreach ($this->includes as $include) {
                if (file_exists($include)) {
                    include $include;
                }
            }
        }
    }

    /**
     * @return void
     */
    public function saveCache()
    {
        file_put_contents($this->cache, $this->dump());
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if (file_exists($this->cache)) {
            $this->saveCache();
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/18
 * Time: 下午2:28
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing;

/**
 * Class RouterCaching
 *
 * @package Dobee\Routing
 */
abstract class RouterCaching
{
    /**
     * @var string
     */
    protected $cachePath = './';

    /**
     * @var string
     */
    protected $cacheName = 'router.php.cache';

    /**
     * @return bool|string
     */
    public function hasCaching()
    {
        $caching = realpath($this->cachePath) . DIRECTORY_SEPARATOR . $this->cacheName;

        return file_exists($caching) ? $caching : false;
    }

    /**
     * @return string
     */
    public function getCacheName()
    {
        return $this->cacheName;
    }

    /**
     * @param string $cacheName
     * @return $this
     */
    public function setCacheName($cacheName)
    {
        $this->cacheName = $cacheName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * @param string $cachePath
     * @return $this
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;

        return $this;
    }

    /**
     * @param null $cachePath
     * @param null $cacheName
     * @return mixed
     */
    abstract public function setCaching($cachePath = null, $cacheName = null);

    /**
     * @param null $cachePath
     * @param null $cacheName
     * @return mixed
     */
    abstract public function getCaching($cachePath = null, $cacheName = null);
}
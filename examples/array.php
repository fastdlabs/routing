<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

class Node
{
    /**
     * 多维数组节点分隔符
     * @var string
     */
    const SP = '.';
    public static function explode($node) {
        return explode(static::SP, $node);
    }
    public static function set(array & $arr, $node, $value = null) {
        $arr = $arr2 = (array) $arr;
        $keys = static::explode($node);
        foreach ($keys as $key) {
            $key = strval($key);
            if (isset($arr[$key])) {
                $arr = & $arr[$key];
            } else {
                $arr[$key] = array();
                $arr = & $arr[$key];
            }
        }
        $arr = $value;
    }
}

echo '<pre>';
// 测试数组1
$arr1 = [];
Node::set($arr1, 'a.b.c.d.e.g', 'http://www.ai9475.com/');
print_r($arr1);
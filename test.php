<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

$regex = '~^/fuzzy([_a-zA-Z0-9-\/]+){1,}$~';

$str = '/fuzzy/foo/bar';

preg_match_all($regex, $str, $matches);

print_r($matches);
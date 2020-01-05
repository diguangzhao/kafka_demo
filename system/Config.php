<?php

/**
 * @Author: diguangzhao
 * @Date:   2017-12-27 23:36:30
 * @Last Modified by:   diguangzhao
 * @Last Modified time: 2018-01-05 00:39:21
 * @功能: 配置信息处理类，
 * @描述: 读取ROOT_PATH/<filename>.php 中 
 *    $config[<config>]变量内容，赋给 self::$config[<filename>][<config>]
 */
namespace Dea;

class Config {

    public static $items;

    public static function setup() {
        self::clear();
        self::fetch();
    }

    public static function export() {
        return self::$items;
    }

    public static function get(string $key, $default = null) {
        list($file, $name) = explode('.', $key, 2);

        if ($name == null) {
            return isset(self::$items[$file]) ? self::$items[$file] : $default;
        }
        return isset(self::$items[$file][$name]) ? self::$items[$file][$name] : $default;
    }

    public static function set(string $key, $value) {
        list($file, $name) = explode('.', $key, 2);

        if ($name === null) {
            self::$items[$file] = $value;
        } else {
            self::$items[$file][$name] = $value;
        }
    }

    public static function append(string $key, $value) {
        list($file, $name) = explode('.', $key, 2);

        if (self::$items[$file][$name] === null) {
            self::$items[$file][$name] = $value;
        } elseif (is_array(self::$items[$file][$name])) {
            self::$items[$file][$name][] = $value;
        } else {
            self::$items[$file][$name] .= $value;
        }
    }

    static function clear() {
        self::$items = [];
    }

    static function fetch() {
        if (!is_dir(CONFIG_PATH)) {
            return;
        }

        $dh = opendir(CONFIG_PATH);
        if ($dh) {
            while ($name = readdir($dh)) {
                if ($name[0] == '.') {
                    continue;
                }

                $file = CONFIG_PATH.'/'.$name;
                if (!is_file($file)) {
                    continue;
                }

                $category = pathinfo($name, PATHINFO_FILENAME);
                if (!isset(self::$items[$category])) {
                    self::$items[$category] = [];
                }

                switch (pathinfo($name, PATHINFO_EXTENSION)) {
                    case 'php':
                        $config = &self::$items[$category];
                        call_user_func(function () use (&$config, $file) {
                            include $file;
                        });
                        break;
                }
            }
        }
        return $config;
    }
}
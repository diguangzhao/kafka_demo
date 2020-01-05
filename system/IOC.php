<?php

namespace Dea;

class IOC {
    protected static $CALLBACKS;
    
    public static function construct() {

        $args = func_get_args();
        $name = array_shift($args);
        $key = self::key($name);

        if (isset(self::$CALLBACKS[$key])) {
            $obj = self::$CALLBACKS[$key];
            if ($obj->singleton) {
                return $obj->object ?: $obj->object = call_user_func_array($obj->callback, $args);
            }
            return call_user_func_array($o->callback, $args);
        }

        $rc = new \ReflectionClass($name);

        return $rc->newInstanceArgs($args);

    }

    protected static function key(string $key) {
        return strtolower($key);
    }

    public static function bind($name, $callback)
    {
        static::$CALLBACKS[self::key($name)] = (object) [
            'callback' => $callback,
            'singleton' => false,
        ];
    }

    public static function singleton($name, $callback)
    {
        static::$CALLBACKS[self::key($name)] = (object) [
            'callback' => $callback,
            'singleton' => true,
        ];
    }
    
    public static function instance($name, $object)
    {
        static::$CALLBACKS[self::key($name)] = (object) [
            'object' => $object,
            'singleton' => true,
        ];
    }

    public static function clear($name)
    {
        unset(static::$CALLBACKS[self::key($name)]);
    }
}
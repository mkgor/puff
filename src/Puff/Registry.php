<?php


namespace Puff;


class Registry
{
    /**
     * @var array
     */
    protected static $container;

    /**
     * @return mixed
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * @param mixed $container
     */
    public static function setContainer($container)
    {
        self::$container = $container;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function add($key, $value)
    {
        self::$container[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return self::$container[$key] ?? null;
    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        if(isset(self::$container[$key])) {
            unset(self::$container[$key]);
        }
    }
}
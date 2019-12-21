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
     * Creating new registry item
     *
     * @param $key
     * @param $value
     */
    public static function add($key, $value)
    {
        self::$container[$key] = $value;
    }

    /**
     * Insert value into existing registry item
     *
     * @param $key
     * @param $value
     */
    public static function insertInto($key,$value)
    {
        self::$container[$key][] = $value;
    }

    /**
     * Insert value into existing registry item with associative key
     *
     * @param $key
     * @param $assocKey
     * @param $value
     */
    public static function insertAssoc($key, $assocKey, $value)
    {
        self::$container[$key][$assocKey] = $value;
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
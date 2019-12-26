<?php


namespace Puff\Modules\Core\Element\ForElement;

/**
 * Class Iterator
 * @package Puff\Modules\Core\Element\ForElement
 */
class Iterator
{
    /**
     * @var array
     */
    private $array;

    /**
     * @var bool
     */
    private $activated = false;

    /**
     * Iterator constructor.
     * @param $array
     */
    public function __construct($array) {
        $this->array = $array;
    }

    /**
     * Resets iterator
     */
    function rewind() {
        reset($this->array);
    }

    /**
     * Returns current item of array
     *
     * @return mixed
     */
    function current() {
        return current($this->array);
    }

    /**
     * Returns key of array
     *
     * @return int|string|null
     */
    function key() {
        return key($this->array);
    }

    /**
     * Returns next item of array
     */
    function next() {
        if(!$this->activated) {
            $this->activated = true;
        } else {
            next($this->array);
        }
    }

    /**
     * @return bool
     */
    function valid() {
        return key($this->array) !== null;
    }

    /**
     * @param $key
     * @return bool
     */
    function exists($key)
    {
        return isset($this->array[$key]);
    }
}
<?php


namespace Puff\Compilation\Element;


interface ElementInterface
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes);

    /**
     * @param $tokenAttributesString
     * @return mixed
     */
    public function handleAttributes($tokenAttributesString);
}
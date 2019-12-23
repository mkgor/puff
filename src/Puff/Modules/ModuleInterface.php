<?php

namespace Puff\Modules;

/**
 * Interface ModuleInterface
 * @package Puff\Modules
 */
interface ModuleInterface
{
    /**
     * Returns an array of elements and filters which will be initialized
     *
     * @return array
     */
    public function setUp(): array;
}
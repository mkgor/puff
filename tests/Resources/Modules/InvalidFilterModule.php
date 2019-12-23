<?php


namespace Tests\Resources\Modules;


use Puff\Modules\ModuleInterface;
use Tests\Resources\InvalidFilter;

class InvalidFilterModule implements ModuleInterface
{

    /**
     * Returns an array of elements and filters which will be initialized
     *
     * @return array
     */
    public function setUp(): array
    {
        return [
            'filters' => [
                'invalid_filter' => InvalidFilter::class
            ]
        ];
    }
}
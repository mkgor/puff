<?php


namespace Tests\Resources\Modules;


use Puff\Modules\ModuleInterface;
use Tests\Resources\TestElement;
use Tests\Resources\TestFilter;

class TestModule implements ModuleInterface
{

    /**
     * Returns an array of elements and filters which will be initialized
     *
     * @return array
     */
    public function setUp(): array
    {
        return [
            'elements' => [
                'test_element' => new TestElement()
            ],
            'filters' => [
                'test_filter' => TestFilter::class
            ]
        ];
    }
}
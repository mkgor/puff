<?php

namespace Puff\Modules\Core;

use Puff\Modules\Core\Element\ElseElement;
use Puff\Modules\Core\Element\EndElement;
use Puff\Modules\Core\Element\ForElement\ForElement;
use Puff\Modules\Core\Element\IfElement;
use Puff\Modules\Core\Element\ImportElement;
use Puff\Modules\Core\Element\SetElement;
use Puff\Modules\Core\Element\ShowElement;
use Puff\Modules\Core\Filter\TransliterationFilter;
use Puff\Modules\Core\Filter\UpperCaseFilter;
use Puff\Modules\ModuleInterface;

/**
 * Class CoreModule
 * @package Puff\Modules
 */
class CoreModule implements ModuleInterface
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
                'show' => new ShowElement(),
                'set' => new SetElement(),
                'import' => new ImportElement(),
                'if' => new IfElement(),
                'for' => new ForElement(),
                'else' => new ElseElement(),
                'end' => new EndElement(),
            ],

            'filters' => [
                'transliterate' => TransliterationFilter::class,
                'uppercase' => UpperCaseFilter::class
            ]
        ];
    }
}
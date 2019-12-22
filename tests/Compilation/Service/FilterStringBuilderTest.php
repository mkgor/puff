<?php

namespace Compilation\Service;

use PHPUnit\Framework\TestCase;
use Puff\Compilation\Service\FilterStringBuilder;
use Puff\Engine;
use Puff\Exception\InvalidFilterException;

class FilterStringBuilderTest extends TestCase
{

    public function testExceptionInvalidFilter()
    {
        $this->expectException(InvalidFilterException::class);

        $engine = new Engine();

        $engine->render(__DIR__ . '/../../Resources/invalid_filter.puff.html', [
            'variable' => 'test'
        ]);
    }
}

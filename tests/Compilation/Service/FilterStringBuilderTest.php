<?php

namespace Compilation\Service;

use PHPUnit\Framework\TestCase;
use Puff\Compilation\Service\FilterStringBuilder;
use Puff\Engine;
use Puff\Exception\InvalidFilterException;
use Puff\Exception\ModuleException;
use Puff\Modules\Core\CoreModule;
use Tests\Resources\Modules\InvalidFilterModule;

class FilterStringBuilderTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testExceptionInvalidFilter()
    {
        $this->expectException(ModuleException::class);

        $engine = new Engine([
            'modules' => [
                new CoreModule(),
                new InvalidFilterModule()
            ]
        ]);

        $engine->render(__DIR__ . '/../../Resources/invalid_filter.puff.html', [
            'variable' => 'test'
        ]);
    }

    public function testExceptionNotExistingFilter()
    {
        $this->expectException(InvalidFilterException::class);

        $engine = new Engine([
            'modules' => [
                new CoreModule(),
            ]
        ]);

        $engine->render(__DIR__ . '/../../Resources/invalid_filter.puff.html', [
            'variable' => 'test'
        ]);
    }

    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testGlobalFunctions()
    {
        $engine = new Engine([
            'modules' => [
                new CoreModule(),
            ]
        ]);

        $result = $engine->render(__DIR__ . '/../../Resources/filter_string_test.puff.html', [
            'integer_value' => 123.123123123
        ]);

        $this->assertEquals('123.123', $result);
    }
}

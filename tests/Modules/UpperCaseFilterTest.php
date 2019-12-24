<?php

namespace Modules;

use Puff\Engine;
use Puff\Modules\Core\CoreModule;
use Puff\Modules\Core\Filter\UpperCaseFilter;
use PHPUnit\Framework\TestCase;

class UpperCaseFilterTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testHandle()
    {
        $engine = new Engine([
            'modules' => [
                new CoreModule()
            ]
        ]);

        $engine->setDirectInputMode(true);

        $this->assertEquals('HELLO', $engine->render('[[ variable ~ uppercase ]]', ['variable' => 'Hello']));
    }

}

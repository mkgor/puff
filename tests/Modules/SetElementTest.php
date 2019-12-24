<?php

namespace Modules;

use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Modules\Core\CoreModule;
use Puff\Modules\Core\Element\SetElement;

class SetElementTest extends TestCase
{

    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testProcess()
    {
        $engine = new Engine([
            'modules' => [
                new CoreModule()
            ]
        ]);

        $engine->setDirectInputMode(true);

        $this->assertEquals('test2', $engine->render('[% set variable = "test2" %][[ variable ]]'));
    }
}

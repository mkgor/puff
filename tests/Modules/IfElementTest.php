<?php

namespace Modules;

use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Modules\Core\CoreModule;
use Puff\Modules\Core\Element\IfElement;

class IfElementTest extends TestCase
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

        $this->assertEquals('test', $engine->render('[% if variable == 1 %]test[% end %]', ['variable' => 1]));
        $this->assertEquals('',$engine->render('[% if variable == 1 %]test[% end %]', ['variable' => 0]));
        $this->assertEquals('not-test',$engine->render('[% if variable == 1 %]test[% else %]not-test[% end %]', ['variable' => 0]));
    }
}

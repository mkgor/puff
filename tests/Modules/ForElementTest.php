<?php

namespace Modules;

use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Modules\Core\CoreModule;
use Puff\Modules\Core\Element\ForElement;

class ForElementTest extends TestCase
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

        $this->assertEquals('123', $engine->render('[% for numbers in number %][[ number ]][% end %]', ['numbers' => [1,2,3]]));
        $this->assertEquals('ABC', $engine->render('[% for letters ~ uppercase in letter %][[ letter ]][% end %]', ['letters' => ['A','B','C']]));
        $this->assertEquals('a:1b:2c:3', $engine->render('[% for alphabet in key,value %][[ key ]]:[[ value]][% end %]', ['alphabet' => [
            'a' => 1,
            'b' => 2,
            'c' => 3
        ]]));

    }
}

<?php

namespace Modules;

use Puff\Engine;
use Puff\Modules\Core\CoreModule;
use Puff\Modules\Core\Filter\TransliterationFilter;
use PHPUnit\Framework\TestCase;

class TransliterationFilterTest extends TestCase
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

        $this->assertEquals('test', $engine->render('[[ variable ~ transliterate ]]', ['variable' => 'тест']));
        $this->assertEquals('yogurt', $engine->render('[[ variable ~ transliterate ]]', ['variable' => 'йогурт']));
    }

}

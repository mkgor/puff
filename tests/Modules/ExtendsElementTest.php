<?php

namespace Modules;

use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Exception\InvalidArgumentException;
use Puff\Modules\Core\CoreModule;
use Puff\Modules\Core\Element\ExtendsElement;

class ExtendsElementTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     * @runInSeparateProcess
     */
    public function testExtends()
    {
        $engine = new Engine([
            'modules' => [
                new CoreModule()
            ]
        ]);

        $engine->setTemplatesPath(__DIR__ . '/../Resources/');

        $result = $engine->render('template.puff.html', [
            'variable1' => 'test',
            'variable2' => 33.33123123214
        ]);

        $this->assertEquals('34test', $result);
    }

    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testExceptionExtends()
    {
        $this->expectException(InvalidArgumentException::class);
        $engine = new Engine([
            'modules' => [
                new CoreModule()
            ]
        ]);

        $engine->setDirectInputMode(true);

        $engine->render('[% extends %]');
    }
}

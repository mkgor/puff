<?php

use Puff\Engine;
use Tests\Resources\TestFilter;

class EngineTest extends \PHPUnit\Framework\TestCase
{
    /** @var Engine */
    private $engineInstance;

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testRender()
    {
        $this->engineInstance = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule(),
                new \Tests\Resources\Modules\TestModule()
            ]
        ]);

        $result = $this->engineInstance->render(__DIR__ . '/Resources/test.puff.html', [
            'variable' => 'test'
        ]);

        $this->assertEquals("test filter test", $result);
    }


    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testExceptionFilterInterfaceImplementation()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'modules' => [
                new \Tests\Resources\Modules\InvalidFilterModule()
            ]
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testInvalidTemplatePath()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule()
            ]
        ]);

        echo $engine->render('invalid.puff.html');
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testBenchmarkBar()
    {
        $engine = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule(),
                new \Tests\Resources\Modules\TestModule()
            ]
        ]);

        $engine->setBenchmarkEnabled(true);

        $this->assertNotEquals("test filter filter", $engine->render(__DIR__ . '/Resources/test.puff.html', [
            'variable' => 'test'
        ]));

        $this->assertTrue($engine->isBenchmarkEnabled());
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testInitializingWithoutModules()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'modules' => []
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testInitializingInvalidModule()
    {
        $this->expectException(\Puff\Exception\ModuleException::class);

        $engine = new Engine([
            'modules' => [
                new \Tests\Resources\Modules\InvalidModule()
            ]
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testInvalidElement()
    {
        $this->expectException(\Puff\Exception\ModuleException::class);

        $engine = new Engine([
            'modules' => [
                new \Tests\Resources\Modules\InvalidElementModule()
            ]
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testNotArrayProvided()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'modules' => 123
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testNotObjectProvided()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'modules' => [123]
        ]);
    }
}

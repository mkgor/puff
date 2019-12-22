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
            'extensions' => [
                'filters' => [
                    'test_filter' => TestFilter::class
                ],

                'elements' => [
                    'test_element' => new Tests\Resources\TestElement()
                ]
            ]
        ]);

        $this->assertEquals("test filter test", $this->engineInstance->render(__DIR__ . '/Resources/test.puff.html', [
            'variable' => 'test'
        ]));
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testExceptionClassNotFound()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'extensions' => [
                'filters' => [
                    'test_filter' => 'Unknown\Class'
                ]
            ]
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testExceptionFilterInterfaceImplementation()
    {
        $this->expectException(\Puff\Exception\PuffException::class);

        $engine = new Engine([
            'extensions' => [
                'filters' => [
                    'test_filter' => \Tests\Resources\InvalidFilter::class
                ]
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

        $engine = new Engine();

        echo $engine->render('invalid.puff.html');
    }

    /**
     * @throws ReflectionException
     * @throws \Puff\Exception\PuffException
     */
    public function testBenchmarkBar()
    {
        $this->engineInstance = new Engine([
            'extensions' => [
                'filters' => [
                    'test_filter' => TestFilter::class
                ],

                'elements' => [
                    'test_element' => new Tests\Resources\TestElement()
                ]
            ]
        ]);

        $this->engineInstance->setBenchmarkEnabled(true);

        $this->assertNotEquals("test filter filter", $this->engineInstance->render(__DIR__ . '/Resources/test.puff.html', [
            'variable' => 'test'
        ]));

        $this->assertTrue($this->engineInstance->isBenchmarkEnabled());
    }
}

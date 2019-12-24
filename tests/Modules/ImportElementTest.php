<?php


namespace Tests\Modules;


use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Exception\InvalidArgumentException;
use Puff\Exception\PuffException;

class ImportElementTest extends TestCase
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $this->engine = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule(),
            ]
        ]);
    }

    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testImport()
    {
        $result = $this->engine->render(__DIR__ . '/../Resources/import.puff.html', [
            'variable' => [
                'item' => 'test'
            ]
        ]);

        $this->assertEquals("test", $result);
    }

    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testSrcNotExists()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->engine->setDirectInputMode(true);

        $result = $this->engine->render('[% import %]');
    }

    /**
     * @throws PuffException
     * @throws \ReflectionException
     */
    public function testTemplateDirTemplateNotFound()
    {
        $_SERVER['DOCUMENT_ROOT'] = '/';

        $this->expectException(PuffException::class);

        $this->engine->setDirectInputMode(true);

        $this->engine->render('[% import src="123.puff.html" %]');
    }
}
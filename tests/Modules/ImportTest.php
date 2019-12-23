<?php


namespace Tests\Modules;


use PHPUnit\Framework\TestCase;
use Puff\Engine;

class ImportTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testImport()
    {
        $engine = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule(),
            ]
        ]);

        $result = $engine->render(__DIR__ . '/../Resources/import.puff.html', [
            'variable' => [
                'item' => 'test'
            ]
        ]);

        $this->assertEquals("test", $result);
    }
}
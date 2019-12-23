<?php


namespace Tests\Factory;


use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Exception\InvalidKeywordException;

/**
 * Class ElementClassFactoryTest
 * @package Tests\Factory
 */
class ElementClassFactoryTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testInvalidKeyword()
    {
        $this->expectException(InvalidKeywordException::class);

        $engine = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule(),
            ]
        ]);

        $engine->render(__DIR__ . '/../Resources/test.puff.html', [
            'variable' => 'test'
        ]);
    }
}
<?php


namespace Test\Compilation;


use PHPUnit\Framework\TestCase;
use Puff\Engine;
use Puff\Modules\Core\CoreModule;
use Tests\Resources\Modules\InvalidFilterModule;

/**
 * Class TagEscapeTest
 *
 * @package Test\Compilation
 */
class TagEscapeTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testEscapeString()
    {
        $engine = new Engine([
            'modules' => [
                new CoreModule(),
            ]
        ]);

        $engine->setDirectInputMode(true);

        $result = $engine->render('[[ variable ]]', ['variable' => 'This is escaped tag']);
        $this->assertEquals('This is escaped tag', $result);

        $result_2 = $engine->render("//[[ variable ]] [[ variable ]]", ['variable' => 'This is escaped tag']);
        $this->assertEquals('[[ variable ]] This is escaped tag', $result_2);
    }
}
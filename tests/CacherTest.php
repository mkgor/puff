<?php


namespace Test;


use PHPUnit\Framework\TestCase;
use Puff\Cacher;
use Puff\Engine;

class CacherTest extends TestCase
{
    /**
     * @throws \Puff\Exception\PuffException
     * @throws \ReflectionException
     */
    public function testCache()
    {
        $this->clear();

        $engine = new Engine([
            'modules' => [
                new \Puff\Modules\Core\CoreModule(),
            ]
        ]);

        $engine->setBenchmarkEnabled(true);
        $engine->setCacheEnabled(true);
        $engine->setCacheDirectory(__DIR__ .'/cache');

        $engine->render(__DIR__ . '/Resources/array_test.puff.html', [
            'variable' => [
                'item' => 'test'
            ]
        ]);

        $this->assertFileExists(__DIR__ . '/cache/array_test.puff_cache.php');

        /** Getting data from cache*/
        $result = $engine->render(__DIR__ . '/Resources/array_test.puff.html', [
            'variable' => [
                'item' => 'test'
            ]
        ]);

        $this->assertEquals(1, preg_match('/cache_hit/', $result));
    }

    private function clear() {
        if (file_exists(__DIR__ . '/cache/')) {
            foreach (glob(__DIR__ . '/cache/*') as $file) {
                unlink($file);
            }
        }

        if(is_dir(__DIR__ . '/cache')) {
            rmdir(__DIR__ . '/cache');
        }
    }
}
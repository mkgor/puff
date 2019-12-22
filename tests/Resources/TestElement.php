<?php

namespace Tests\Resources;

use Puff\Compilation\Element\AbstractElement;

class TestElement extends AbstractElement
{
    public function process(array $attributes)
    {
        return "<?php echo 'test'; ?>";
    }
}
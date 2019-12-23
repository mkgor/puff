<?php


namespace Puff\Modules\Core\Element;

use Puff\Compilation\Element\AbstractElement;

/**
 * Class EndElement
 * @package Puff\Compilation\Element
 */
class EndElement extends AbstractElement
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes)
    {
        return "<?php } ?>";
    }
}
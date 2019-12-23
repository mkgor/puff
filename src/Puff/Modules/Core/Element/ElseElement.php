<?php


namespace Puff\Modules\Core\Element;

use Puff\Compilation\Element\AbstractElement;

/**
 * Class ElseElement
 * @package Puff\Compilation\Element
 */
class ElseElement extends AbstractElement
{

    /**
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes)
    {
        return "<?php } else { ?>";
    }
}
<?php

namespace Puff\Compilation\Element;

use Puff\Compilation\Service\VariableTransliterator;
use Puff\Exception\PuffException;

/**
 * Class Show
 * @package Puff\Compilation\Element
 */
class ShowElement extends AbstractElement
{
    /**
     * @param array $attributes
     *
     * @return mixed
     * @throws PuffException
     */
    public function process(array $attributes)
    {
        if (!isset($attributes['data-source'])) {
            throw new PuffException('Expected data-source to print value');
        }

        /** Building PHP snippet which will display data from variable  */
        return sprintf("<?php echo %s; ?>", VariableTransliterator::transliterate($attributes['data-source']));
    }
}
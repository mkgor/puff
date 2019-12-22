<?php

namespace Puff\Compilation\Element;

use Puff\Compilation\Service\FilterStringBuilder;
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
        $filtersStringBuilder = new FilterStringBuilder();

        if (!isset($attributes['data-source'])) {
            throw new PuffException('Expected data-source to print value');
        }

        $filtersString = null;
        $compiledVariable = VariableTransliterator::transliterate($attributes['data-source']);

        if(is_array($attributes['filters'])) {
            $filtersString = $filtersStringBuilder->buildString($attributes['filters'], $compiledVariable);
        }

        /** Building PHP snippet which will display data from variable  */
        return sprintf(" %s <?php echo %s; ?>", $filtersString, $compiledVariable);
    }
}
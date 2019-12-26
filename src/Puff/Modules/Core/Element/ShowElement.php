<?php

namespace Puff\Modules\Core\Element;

use Puff\Compilation\Element\AbstractElement;
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

        $filtersString = null;
        $compiledVariable = VariableTransliterator::transliterate($attributes['data-source']);

        if(!isset($attributes['filters']) || !in_array('unsafe', $attributes['filters'])) {
            $compiledVariable = sprintf("htmlentities(%s)", $compiledVariable);
        }

        if(is_array($attributes['filters'])) {
            $builderResult = $filtersStringBuilder->buildString($attributes['filters'], $compiledVariable);
            $compiledVariable = $builderResult['tmp_variable'];
            $filtersString = $builderResult['compiled'];
        }

        /** Building PHP snippet which will display data from variable  */
        return sprintf("%s<?php echo %s; ?>", $filtersString, (empty($compiledVariable)) ? "''" : $compiledVariable);
    }
}
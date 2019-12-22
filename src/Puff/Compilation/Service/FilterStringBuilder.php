<?php

namespace Puff\Compilation\Service;

use Puff\Exception\InvalidFilterException;
use Puff\Registry;

/**
 * Class FilterStringBuilder
 * @package Compilation\Service
 */
class FilterStringBuilder
{
    /**
     * @param array $filters
     * @param $compiledVariable
     * @return string
     * @throws InvalidFilterException
     */
    public function buildString(array $filters, $compiledVariable)
    {
        $registeredFiltersList = Registry::get('registered_filters');

        $filtersString = '<?php ';

        foreach ($filters as $filter) {
            $arguments = null;

            if(strpos($filter, '(')) {
                preg_match_all('/(?<name>\w+)|\((?<arguments>(.*))\)/', $filter, $matches, PREG_SET_ORDER, 0);

                $filter = $matches[0]['name'];
                $arguments = ',' . $matches[1]['arguments'];
            }

            if(isset($registeredFiltersList[$filter])) {
                $filtersString .= $compiledVariable . ' = ' . $registeredFiltersList[$filter] . '::handle(' . $compiledVariable . $arguments .');' . PHP_EOL;
            } else {
                throw new InvalidFilterException($filter);
            }
        }

        $filtersString .= ' ?>';

        return $filtersString;
    }
}
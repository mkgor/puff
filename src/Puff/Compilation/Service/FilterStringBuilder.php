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
     * @return array
     * @throws InvalidFilterException
     * @throws \Exception
     */
    public function buildString(array $filters, $compiledVariable)
    {
        $registeredFiltersList = Registry::get('registered_filters');
        $newCompiledVariable = $compiledVariable;

        $filtersString = '<?php ';

        foreach ($filters as $filter) {
            $arguments = null;

            if (strpos($filter, '(')) {
                preg_match_all('/(?<name>\w+)|\((?<arguments>(.*))\)/', $filter, $matches, PREG_SET_ORDER, 0);

                $filter = $matches[0]['name'];
                $arguments = ',' . $matches[1]['arguments'];
            }

            if (isset($registeredFiltersList[$filter])) {
                $newCompiledVariable = $this->generateTmpVariable();
                $filtersString .= $newCompiledVariable . ' = ' . $registeredFiltersList[$filter] . '::handle(' . $compiledVariable . $arguments . ');' . PHP_EOL;
            } else if(function_exists($filter)) {
                $newCompiledVariable = $this->generateTmpVariable();
                $filtersString .= $newCompiledVariable . ' = ' . $filter . '(' . $compiledVariable . $arguments . ');' . PHP_EOL;
            } else {
                ob_end_clean();

                throw new InvalidFilterException($filter);
            }
        }

        $filtersString .= ' ?>';

        return ['tmp_variable' => $newCompiledVariable, 'compiled' => $filtersString];
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateTmpVariable()
    {
        return '$tmp_' . random_int(0, 10000);
    }
}
<?php


namespace Puff\Compilation\Element;


use Puff\Exception\PuffException;

class Show implements ElementInterface
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

        return "<?php echo $" . preg_replace_callback('/\.(?<var>\w+)/', function ($result) {
            return "['{$result['var']}']";
        }, $attributes['data-source']) . '; ?>';
    }
}
<?php


namespace Puff\Modules\Core\Element;


use Puff\Compilation\Compiler;
use Puff\Compilation\Element\AbstractElement;
use Puff\Exception\InvalidArgumentException;
use Puff\Registry;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Tokenizer;

class ExtendsElement extends AbstractElement
{
    public function process(array $attributes)
    {
        if(!isset($attributes['src'])) {
            ob_end_clean();

            throw new InvalidArgumentException('src', 'nothing', __CLASS__);
        }

        $tokenizer = new Tokenizer(new TokenRepository());
        $compiler = new Compiler();

        $templateString = file_get_contents(Registry::get('template_path') . DIRECTORY_SEPARATOR . $attributes['src']);

        $extendsID = 'extends_' . random_int(0,100000);

        Registry::add($extendsID, $templateString);
        Registry::add('main_template_string', $compiler->compile($tokenizer->tokenize($templateString), $extendsID));

        return null;
    }
}
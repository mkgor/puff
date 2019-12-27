<?php


namespace Puff\Modules\Core\Element;


use Puff\Compilation\Compiler;
use Puff\Compilation\Element\AbstractElement;
use Puff\Registry;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Tokenizer;

class PositionElement extends AbstractElement
{
    public function process(array $attributes)
    {
        $tokenizer = new Tokenizer(new TokenRepository());
        $compiler = new Compiler();

        if(isset($attributes['for'])) {
            $code = trim(Registry::get('position_' . $attributes['for']));

            return $compiler->compile($tokenizer->tokenize($code ?? ''), 'position_' . $attributes['for']);
        } else if(isset($attributes['name'])) {
            $mainTemplateString = Registry::get('main_template_string');

            preg_match("/\[%\s*position\s+name=['\"]".preg_quote($attributes['name'])."['\"]\s+%\]([\s\S]+?)\[%\s*endposition.*%\]/",$mainTemplateString , $matches);

            Registry::add('position_' . $attributes['name'], trim($matches[1]));
        }

        return null;
    }
}
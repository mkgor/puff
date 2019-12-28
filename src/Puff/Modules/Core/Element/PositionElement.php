<?php


namespace Puff\Modules\Core\Element;


use Puff\Compilation\Compiler;
use Puff\Compilation\Element\AbstractElement;
use Puff\Registry;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Syntax\SyntaxInterface;
use Puff\Tokenization\Tokenizer;

/**
 * Class PositionElement
 * @package Puff\Modules\Core\Element
 */
class PositionElement extends AbstractElement
{
    /**
     * @param array $attributes
     * @return mixed|string|void|null
     * @throws \Puff\Exception\InvalidKeywordException
     * @throws \Puff\Exception\PuffException
     */
    public function process(array $attributes)
    {
        /** @var SyntaxInterface $syntax */
        $syntax = Registry::get('syntax');

        $elementTag = $syntax->getElementTag();

        $tokenizer = new Tokenizer(new TokenRepository());
        $compiler = new Compiler();

        if(isset($attributes['for'])) {
            $code = Registry::get('position_' . $attributes['for']);

            return $compiler->compile($tokenizer->tokenize($code ?? ''), 'position_' . $attributes['for']);
        } else if(isset($attributes['name'])) {
            $mainTemplateString = Registry::get('main_template_string');

            preg_match("/".preg_quote($elementTag[0])."\s*position\s+name=['\"]".preg_quote($attributes['name'])."['\"]\s+".preg_quote($elementTag[1])."([\s\S]+?)".preg_quote($elementTag[0])."\s*endposition.*".preg_quote($elementTag[1])."/",$mainTemplateString , $matches);

            $code =  preg_replace_callback('/(^\s+|^\t+)/m', function() {
                return null;
            }, $matches[1]);

            Registry::add('position_' . $attributes['name'], trim($code));
        }

        return null;
    }
}
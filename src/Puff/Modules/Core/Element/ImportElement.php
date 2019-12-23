<?php

namespace Puff\Modules\Core\Element;

use Puff\Compilation\Compiler;
use Puff\Compilation\Element\AbstractElement;
use Puff\Exception\InvalidKeywordException;
use Puff\Registry;
use Puff\Exception\InvalidArgumentException;
use Puff\Exception\PuffException;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Tokenizer;

/**
 * Class Import
 * @package Puff\Compilation\Element
 */
class ImportElement extends AbstractElement
{
    /**
     * @param array $attributes
     * @return mixed
     * @throws InvalidArgumentException
     * @throws InvalidKeywordException
     * @throws PuffException
     */
    public function process(array $attributes)
    {
        if(!isset($attributes['src'])) {
            throw new InvalidArgumentException('src', 'nothing', __CLASS__);
        }

        $tokenizer = new Tokenizer(new TokenRepository());
        $compiler = new Compiler();

        $templatesDirectory = Registry::get('template_path');

        if(empty($templatesDirectory)) {
            $templatesDirectory = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        }

        $templatePath = $templatesDirectory . '/' . $attributes['src'];

        if(file_exists($templatePath)) {
            $importedTemplate = file_get_contents($templatePath);
        } else {
            throw new PuffException("Template import error. Could not find template on `".$templatePath."`");
        }

        return $compiler->compile($tokenizer->tokenize($importedTemplate), $importedTemplate);
    }
}
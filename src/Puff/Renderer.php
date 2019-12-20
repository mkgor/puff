<?php


namespace Puff;


use Puff\Compilation\Compiler;
use Puff\Registry;
use Puff\Exception\PuffException;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Repository\TokenRepositoryInterface;
use Puff\Tokenization\Tokenizer;

/**
 * Class Renderer
 *
 * @package Puff
 */
class Renderer
{
    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * @var string
     */
    private $templatesPath = null;

    /**
     * @var string
     */
    private $renderedTemplateString;

    /**
     * @return string
     */
    public function getRenderedTemplateString()
    {
        return $this->renderedTemplateString;
    }

    /**
     * @param string $renderedTemplateString
     */
    public function setRenderedTemplateString($renderedTemplateString)
    {
        $this->renderedTemplateString = $renderedTemplateString;
    }

    /**
     * @return string
     */
    public function getTemplatesPath()
    {
        return $this->templatesPath;
    }

    /**
     * @param string $templatesPath
     */
    public function setTemplatesPath($templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    /**
     * @return TokenRepositoryInterface
     */
    public function getTokenRepository()
    {
        return $this->tokenRepository;
    }

    /**
     * @param TokenRepositoryInterface $tokenRepository
     */
    public function setTokenRepository(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Renderer constructor.
     */
    public function __construct()
    {
        /**
         * Initializing default TokenRepository class, it can be replaced by calling `setTokenRepository` before rendering
         *
         * @var TokenRepository tokenRepository
         */
        $this->tokenRepository = new TokenRepository();
    }

    /**
     * Renders template or takes it from cache
     *
     * @param $template
     * @param array $vars
     *
     * @return mixed|string
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\InvalidKeywordException
     * @throws Exception\PuffException
     */
    public function render($template, array $vars = [])
    {
        $tokenizer = new Tokenizer($this->getTokenRepository());
        $compiler = new Compiler();

        /** Injecting variables into the template */
        extract($vars);

        /** Starting output buffering */
        ob_start();

        if (file_exists($this->getTemplatesPath() . $template)) {
            $templateString = file_get_contents($this->getTemplatesPath() . $template);

            Registry::add('template_path', $this->getTemplatesPath());

            $this->setRenderedTemplateString($compiler->compile($tokenizer->tokenize($templateString), $templateString));

            /** Trying to run compiled template with `eval` */
            try {
                eval("?>" . $this->getRenderedTemplateString() . "<?");
            } catch (\Exception $e) {
                throw new PuffException($e->getMessage());
            }
        } else {
            throw new PuffException('Template not found on ' . $this->getTemplatesPath() . $template);
        }

        /** Returning compiled HTML code */
        return ob_get_clean();
    }
}
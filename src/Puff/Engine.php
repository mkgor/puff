<?php

namespace Puff;

use Exception;
use Puff\Compilation\Filter\FilterInterface;
use Puff\Compilation\Filter\TransliterationFilter;
use Puff\Compilation\Filter\UpperCaseFilter;
use Puff\Compilation\Compiler;
use Puff\Exception\PuffException;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Repository\TokenRepositoryInterface;
use Puff\Tokenization\Tokenizer;
use ReflectionClass;
use ReflectionException;

/**
 * Class Engine
 *
 * @package Puff
 */
class Engine
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
     * @var bool
     */
    private $benchmarkEnabled = false;

    /**
     * @return bool
     */
    public function isBenchmarkEnabled()
    {
        return $this->benchmarkEnabled;
    }

    /**
     * @param bool $benchmarkEnabled
     */
    public function setBenchmarkEnabled($benchmarkEnabled)
    {
        $this->benchmarkEnabled = $benchmarkEnabled;
    }

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
     * Engine constructor.
     * @param array $config
     * @throws PuffException
     * @throws ReflectionException
     */
    public function __construct(array $config = [])
    {
        /**
         * Initializing default TokenRepository class, it can be replaced by calling `setTokenRepository` before rendering
         *
         * @var TokenRepository tokenRepository
         */
        $this->tokenRepository = new TokenRepository();

        Registry::add('custom_keywords', []);
        Registry::add('registered_filters', [
            'uppercase' => UpperCaseFilter::class,
            'translit' => TransliterationFilter::class
        ]);

        /** Registering custom elements */
        if(isset($config['extensions']['elements'])) {
            foreach ($config['extensions']['elements'] as $key => $item) {
                Registry::insertAssoc('custom_keywords', $key, $item);
            }
        }

        /** Registering custom filters */
        if(isset($config['extensions']['filters'])) {
            foreach($config['extensions']['filters'] as $key => $item) {
                if(class_exists($item)) {
                    $filterClassReflection = new ReflectionClass($item);

                    if (!$filterClassReflection->implementsInterface(FilterInterface::class)) {
                        throw new PuffException(sprintf('Filter `%s` is not implementing %s', $key, FilterInterface::class));
                    }

                    Registry::insertAssoc('registered_filters', $key, $item);
                } else {
                    throw new PuffException(sprintf('Filter with class %s not found', $item));
                }
            }
        }
    }

    /**
     * Renders template or takes it from cache
     *
     * @param $template
     * @param array $vars
     *
     * @return mixed|string
     *
     * @throws PuffException
     * @throws ReflectionException
     */
    public function render($template, array $vars = [])
    {
        $startingMark = microtime(true);
        $startingMemoryUsage = memory_get_usage();

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
            } catch (Exception $e) {
                throw new PuffException($e->getMessage());
            }
        } else {
            throw new PuffException('Template not found on ' . $this->getTemplatesPath() . $template);
        }

        $endingMark = microtime(true);
        $endingMemoryUsage = memory_get_usage();

        $benchmarkResults = [
            'time' => $endingMark - $startingMark,
            'memory_usage' => round(($endingMemoryUsage - $startingMemoryUsage)/1024, 1)
        ];

        if($this->isBenchmarkEnabled()) {
            $engine = new Engine();

            echo $engine->render(__DIR__ . '/Resources/templates/benchmark.puff.html', $benchmarkResults);
        }

        /** Returning compiled HTML code */
        return ob_get_clean();
    }
}
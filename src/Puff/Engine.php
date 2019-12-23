<?php

namespace Puff;

use Exception;
use Puff\Compilation\Filter\FilterInterface;
use Puff\Compilation\Filter\TransliterationFilter;
use Puff\Compilation\Filter\UpperCaseFilter;
use Puff\Compilation\Compiler;
use Puff\Exception\PuffException;
use Puff\Modules\ModuleInterface;
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
     * @var array
     */
    private $initializedModules = [];

    /**
     * @return array
     */
    public function getInitializedModules(): array
    {
        return $this->initializedModules;
    }

    /**
     * @param array $initializedModules
     */
    public function setInitializedModules(array $initializedModules): void
    {
        $this->initializedModules = $initializedModules;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function isBenchmarkEnabled()
    {
        return $this->benchmarkEnabled;
    }

    /**
     * @param bool $benchmarkEnabled
     * @codeCoverageIgnore
     */
    public function setBenchmarkEnabled($benchmarkEnabled)
    {
        $this->benchmarkEnabled = $benchmarkEnabled;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getRenderedTemplateString()
    {
        return $this->renderedTemplateString;
    }

    /**
     * @param string $renderedTemplateString
     * @codeCoverageIgnore
     */
    public function setRenderedTemplateString($renderedTemplateString)
    {
        $this->renderedTemplateString = $renderedTemplateString;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getTemplatesPath()
    {
        return $this->templatesPath;
    }

    /**
     * @param string $templatesPath
     * @codeCoverageIgnore
     */
    public function setTemplatesPath($templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    /**
     * @return TokenRepositoryInterface
     * @codeCoverageIgnore
     */
    public function getTokenRepository()
    {
        return $this->tokenRepository;
    }

    /**
     * @param TokenRepositoryInterface $tokenRepository
     * @codeCoverageIgnore
     */
    public function setTokenRepository(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Engine constructor.
     * @param array $configuration
     *
     * @throws PuffException
     * @throws ReflectionException
     */
    public function __construct(array $configuration = [])
    {
        /**
         * Initializing default TokenRepository class, it can be replaced by calling `setTokenRepository` before rendering
         *
         * @var TokenRepository tokenRepository
         */
        $this->tokenRepository = new TokenRepository();

        $registeredKeywords = [];
        $registeredFilters = [];

        if(!isset($configuration['modules']) || empty($configuration['modules'])) {
            throw new PuffException('There are no modules initialized.');
        }

        /** @var ModuleInterface $module */
        foreach($configuration['modules'] as $module) {
            if(is_object($module)) {
                $moduleReflection = new ReflectionClass($module);

                $this->initializedModules[] = $moduleReflection->getShortName();

                if (!($module instanceof ModuleInterface)) {
                    throw new PuffException(sprintf('%s is not valid module', get_class($module)));
                }

                $moduleData = $module->setUp();

                if(isset($moduleData['elements'])) {
                    $registeredKeywords = array_merge($registeredKeywords, $moduleData['elements']);
                }

                if(isset($moduleData['filters'])) {
                    $registeredFilters = array_merge($registeredFilters, $moduleData['filters']);
                }
            } else {
                throw new PuffException('Invalid value provided to engine constructor');
            }
        }

        Registry::add('initialized_modules', $this->initializedModules);

        Registry::add('registered_elements', $registeredKeywords);
        Registry::add('registered_filters', $registeredFilters);
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

            eval("?>" . $this->getRenderedTemplateString() . "<?");

        } else {
            ob_end_clean();

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
        $result = ob_get_clean();

        return $result;
    }
}
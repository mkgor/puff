<?php

namespace Puff;

use Exception;
use Puff\Compilation\Element\AbstractElement;
use Puff\Compilation\Filter\FilterInterface;
use Puff\Compilation\Compiler;
use Puff\Exception\ModuleException;
use Puff\Exception\PuffException;
use Puff\Modules\ModuleInterface;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Repository\TokenRepositoryInterface;
use Puff\Tokenization\Syntax\AbstractSyntax;
use Puff\Tokenization\Syntax\BaseSyntax;
use Puff\Tokenization\Syntax\SyntaxInterface;
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
    const BASE_PATH = __DIR__;

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
     * @var bool
     */
    private $directInputMode = false;

    /**
     * @var string
     */
    private $cacheDirectory;

    /**
     * @var bool
     */
    private $cacheEnabled = false;

    /**
     * @var SyntaxInterface
     */
    private $syntax;

    /**
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    /**
     * @param bool $cacheEnabled
     * @codeCoverageIgnore
     */
    public function setCacheEnabled(bool $cacheEnabled): void
    {
        $this->cacheEnabled = $cacheEnabled;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getCacheDirectory(): ?string
    {
        return $this->cacheDirectory;
    }

    /**
     * @param string $cacheDirectory
     * @codeCoverageIgnore
     */
    public function setCacheDirectory(?string $cacheDirectory): void
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @return SyntaxInterface
     * @codeCoverageIgnore
     */
    public function getSyntax(): SyntaxInterface
    {
        return $this->syntax;
    }

    /**
     * @param SyntaxInterface $syntax
     */
    public function setSyntax(SyntaxInterface $syntax): void
    {
        Registry::add('syntax', $syntax);

        $this->syntax = $syntax;
    }


    /**
     * @return bool
     */
    public function isDirectInputMode(): bool
    {
        return $this->directInputMode;
    }

    /**
     * @param bool $directInputMode
     */
    public function setDirectInputMode(bool $directInputMode): void
    {
        $this->directInputMode = $directInputMode;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getInitializedModules(): array
    {
        return $this->initializedModules;
    }

    /**
     * @param array $initializedModules
     * @codeCoverageIgnore
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
        Registry::add('templates_path', $templatesPath);

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

        $this->setSyntax(new BaseSyntax());

        if (!isset($configuration['modules']) || empty($configuration['modules']) || !is_array($configuration['modules'])) {
            throw new PuffException('There are no modules initialized.');
        }

        /** @var ModuleInterface $module */
        foreach ($configuration['modules'] as $module) {
            if (is_object($module)) {
                $moduleReflection = new ReflectionClass($module);

                $this->initializedModules[] = $moduleReflection->getShortName();

                if (!($module instanceof ModuleInterface)) {
                    throw new ModuleException(sprintf('%s is not valid module', get_class($module)));
                }

                $moduleData = $module->setUp();

                if (isset($moduleData['elements'])) {
                    foreach ($moduleData['elements'] as $key => $elementInstance) {
                        if (is_object($elementInstance) && $elementInstance instanceof AbstractElement) {
                            $registeredKeywords[$key] = $elementInstance;
                        } else {
                            throw new ModuleException(sprintf("Element %s in module %s is invalid", $key, $moduleReflection->getShortName()));
                        }
                    }
                }

                if (isset($moduleData['filters'])) {
                    foreach ($moduleData['filters'] as $key => $filterClassname) {
                        $filterReflection = new ReflectionClass($filterClassname);

                        if ($filterReflection->implementsInterface(FilterInterface::class)) {
                            $registeredFilters[$key] = $filterClassname;
                        } else {
                            throw new ModuleException(sprintf("Filter %s in module %s is invalid", $key, $moduleReflection->getShortName()));
                        }
                    }
                    $registeredFilters = array_merge($registeredFilters, $moduleData['filters']);
                }

                if(isset($moduleData['syntax'])) {
                    $this->setSyntax($moduleData['syntax']);
                }
            } else {
                throw new PuffException('Invalid value provided to engine constructor');
            }
        }

        if(isset($configuration['syntax'])) {
            $this->setSyntax($configuration['syntax']);
        }

        Registry::addMultiple([
            'initialized_modules' => $this->initializedModules,
            'registered_elements' => $registeredKeywords,
            'registered_filters'  => $registeredFilters
        ]);
    }

    /**
     * Recursively replaces - and . in array keys by _
     * @param array $input
     * @return array
     */
    public function replaceKeys(array $input)
    {
        $return = [];

        foreach ($input as $key => $value) {
            $key = preg_replace('/[.-]/', '_', $key);

            if (is_array($value)) {
                $value = $this->replaceKeys($value);
            }

            $return[$key] = $value;
        }
        return $return;
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

        $headersArray = [];
        $headersList = headers_list();

        $fullpath = $this->getTemplatesPath() . $template;

        foreach ($headersList as $item) {
            list($header, $content) = explode(':', $item);

            $headersArray[$header] = $content;
        }

        $vars['server_request'] = [
            'globals' => [
                'get' => $_GET,
                'post' => $_POST,
                'request' => $_REQUEST,
                'server' => $_SERVER,
                'cookie' => $_COOKIE,
                'session' => $_SESSION ?? null,
            ],
            'headers' => $headersArray
        ];

        $vars = $this->replaceKeys($vars);

        /** Injecting variables into the template */
        extract($vars);

        /** Starting output buffering */
        ob_start();

        if(!$this->isDirectInputMode()) {
            if (file_exists($fullpath)) {
                $templateString = file_get_contents($fullpath);
            } else {
                ob_end_clean();

                throw new PuffException('Template not found on ' . $fullpath);
            }
        } else {
            $templateString = $template;
        }
        Registry::add('template_path', $this->getTemplatesPath());

        if($this->isCacheEnabled()) {
            Registry::add('cache_enabled', true);
            Registry::add('cache_dir', $this->getCacheDirectory());

            $cacher = new Cacher($this->getCacheDirectory());

            if ($cache = $cacher->get($fullpath, $templateString)) {
                $this->setRenderedTemplateString($cache);
            } else {
                $compiled = $compiler->compile($tokenizer->tokenize($templateString), $templateString);
                $this->setRenderedTemplateString($compiled);

                $cacher->write($fullpath, $templateString, $compiled);
            }
        } else {
            Registry::add('cache_enabled', false);

            $this->setRenderedTemplateString($compiler->compile($tokenizer->tokenize($templateString), $templateString));
        }

        eval("?>" . $this->getRenderedTemplateString());

        $endingMark = microtime(true);
        $endingMemoryUsage = memory_get_usage();

        $benchmarkResults = [
            'time' => $endingMark - $startingMark,
            'memory_usage' => round(($endingMemoryUsage - $startingMemoryUsage) / 1024, 1)
        ];

        if ($this->isBenchmarkEnabled()) {
            $engine = new Engine([
                'modules' => [
                    new \Puff\Modules\Core\CoreModule(),
                ]
            ]);

            if($this->isCacheEnabled()) {
                $engine->setCacheDirectory($this->getCacheDirectory() ?? __DIR__ . '/../cache');
                $engine->setCacheEnabled(true);
            }

            echo $engine->render(__DIR__ . '/Resources/templates/benchmark.puff.html', $benchmarkResults);
        }

        /** Returning compiled HTML code */
        $result = ob_get_clean();

        return trim($result);
    }
}
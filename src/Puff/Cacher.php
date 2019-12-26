<?php


namespace Puff;

use Puff\Exception\PuffException;
use function foo\func;

/**
 * Class Cacher
 * @package Puff
 */
class Cacher
{
    const HASH_ALGO = 'crc32b';

    /**
     * @var string
     */
    private $cacheDirectory;

    /**
     * Cacher constructor.
     *
     * @param $cacheDirectory
     */
    public function __construct($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @param string $filename
     * @param string $data
     * @param string $compiled
     */
    public function write(string $filename, string $data, string $compiled)
    {
        if(!is_dir($this->cacheDirectory)) {
            mkdir($this->cacheDirectory);
        }

        $filedata = $this->decomposeFilepath($filename);

        $cacheData = sprintf("
            <?php
            //Puff template cache file
            //Generated at: %d
            return [
                'hash' => '%s',
                'data' => <<<CODE
                %s
CODE
            ];
            ", time(), hash(self::HASH_ALGO, $data), '<!-- cache_hit -->' . preg_replace_callback('/\$\w+/',function ($item) {
                return sprintf('\%s', $item[0]);
        },$compiled));

        file_put_contents($this->cacheDirectory . DIRECTORY_SEPARATOR . $filedata['cacheFilename'], $cacheData);
    }

    /**
     * @param string $filename
     * @param string $data
     *
     * @return bool
     */
    public function get(string $filename, string $data)
    {
        $filedata = $this->decomposeFilepath($filename);

        if(file_exists($this->cacheDirectory . DIRECTORY_SEPARATOR . $filedata['cacheFilename'])) {
            $cacheFileContent = include($this->cacheDirectory . DIRECTORY_SEPARATOR . $filedata['cacheFilename']);

            if($cacheFileContent['hash'] != hash(self::HASH_ALGO, $data)) {
                return false;
            } else {
                return $cacheFileContent['data'];
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $path
     * @return array
     */
    private function decomposeFilepath(string $path)
    {
        $pathExploded = explode(DIRECTORY_SEPARATOR, realpath($path));

        $filename = array_pop($pathExploded);
        $filenameExploded = explode('.', $filename);
        $fileExtension = array_pop($filenameExploded);

        $cacheFileName = sprintf('%s_cache.php', implode('.',$filenameExploded));

        return [
            'filename' => $filename,
            'extension' => $fileExtension,
            'cacheFilename' => $cacheFileName,
        ];
    }
}
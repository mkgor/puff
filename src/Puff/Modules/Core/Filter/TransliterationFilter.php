<?php

namespace Puff\Modules\Core\Filter;

use Puff\Compilation\Filter\FilterInterface;

class TransliterationFilter implements FilterInterface
{
    /**
     * @param $variable
     * @param mixed ...$args
     * @return mixed
     */
    public static function handle($variable, ...$args)
    {
        return self::translit($variable);
    }

    /**
     * Converts cyrillic symbols to english
     *
     * @param $s
     * @return mixed|string|string[]|null
     */
    private static function translit($s) {
        $s = (string) $s;

        $s = strip_tags($s);
        $s = str_replace(array("\n", "\r"), " ", $s);
        $s = preg_replace("/\s+/", ' ', $s);
        $s = trim($s);
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s);
        $s = str_replace(" ", "-", $s);

        return $s;
    }
}
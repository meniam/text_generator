<?php

namespace TextGenerator;

class TextGenerator
{
    protected static $replaceList;

    public static function factory($template, array $options = array())
    {
        $template = (string)$template;

        if ($replaceList = self::getReplaceList()) {
            $template = str_replace(array_keys($replaceList), array_values($replaceList), $template);
        }

        if (preg_match_all('#(?:\[|\{)((?:(?:[^\[\{\]\}]+)|(?R))*)(?:\]|\})#', $template, $m) > 1) {
            return new Part($template, $options);
        }

        if (mb_strpos($template, '{', null, 'UTF-8') === 0) {
            $template = mb_substr($template, 1, -1, 'UTF-8');
            return new XorPart($template, $options);
        }

        if (mb_strpos($template, '[', null, 'UTF-8') === 0) {
            $template = mb_substr($template, 1, -1, 'UTF-8');
            return new OrPart($template, $options);
        }

        return new Part($template, $options);
    }

    public static function getReplaceList()
    {
        return self::$replaceList;
    }

    public static function addReplaceList($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => &$v) {
                self::addReplace($k, $v);
            }
        }
    }

    public static function addReplace($name, $value)
    {
        $name = trim($name, '%');
        $name = '%%' . $name . '%%';
        self::$replaceList[$name] = (string)$value;
    }
}
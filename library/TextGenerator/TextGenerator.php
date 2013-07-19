<?php

namespace TextGenerator;

class TextGenerator
{
    protected $replaceList = array();

    public static function factory($template, array $options = array())
    {
        $template = (string)$template;

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
}
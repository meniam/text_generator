<?php

namespace TextGenerator;

class TextGenerator
{
    public static function factory($template, array $options = array())
    {
        $template = trim($template);
        if (mb_strpos(trim($template), '{', null, 'utf8') === 0) {
            $template = mb_substr($template, 1, -1, 'UTF-8');
            return new XorPart($template, $options);
        }
        if (mb_strpos($template, '[', null, 'utf8') === 0) {
            $template = mb_substr($template, 1, -1, 'UTF-8');
            return new OrPart($template, $options);
        }
        //print_r($template . "\n");
        return new Part($template, $options);
    }
}
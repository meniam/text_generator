<?php

namespace TextGenerator;

class TextGenerator
{

    public static function factory($template)
    {
        $template = trim($template);
        if (mb_strpos($template, '{', null, 'utf8') === 0) {
            $template = trim($template, '{}');
            return new XorPart($template);
        }
        if (mb_strpos($template, '[', null, 'utf8') === 0) {
            $template = trim($template, '[] ');
            return new OrPart($template);
        }
        //print_r($template . "\n");
        return new Part($template);
    }

}
<?php

require_once 'TextGenerator/Part.php';
require_once 'TextGenerator/OrPart.php';

class TextGenerator
{

    public static function factory($template)
    {
        $template = trim($template);
        if (mb_strpos($template, '{', null, 'utf8') === 0) {
            $template = trim($template, '{}');
        }
        if (mb_strpos($template, '[', null, 'utf8') === 0) {
            $template = trim($template, '[]');
            return new TextGenerator_OrPart($template);
        }
        //print_r($template . "\n");
        return new TextGenerator_Part($template);
    }

}
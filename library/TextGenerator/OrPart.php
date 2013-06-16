<?php

class TextGenerator_OrPart extends TextGenerator_Part
{
    private $templateArray = array();

    public function __construct($template)
    {
        $templateArray = array();
        $template = preg_replace_callback('#[^\|]*(?:\[|\{)((?:(?:[^\[\{\]\}]+)|(?R))*)(?:\]|\})[^\|]*#', function($match) use (&$templateArray) {
            $key = '%%' . count($templateArray);
            $templateArray[$key] = $match[0];
            return $key;
        }, $template);
        $tplArray = explode('|', $template);
        for ($i = 0, $count = count($tplArray); $i < $count; $i++) {
            $tpl = $tplArray[$i];
            if (mb_strpos($tpl, '%%', null, 'utf8') === 0) {
                $tpl = $templateArray[$tpl];
                $this->templateArray[] = $this->parseTemplate($tpl);
            } else {
                $this->templateArray[] = array(
                    'template'          => $tpl,
                    'replacement_array' => ''
                );
            }
        }
    }

    public function generateText()
    {
        $template = $this->templateArray[0];
        $replacementArray = $template['replacement_array'];
        if ($replacementArray) {
            $replacementArray = array_map(function ($value) {
                /** @var $value TextGenerator_Part */
                return $value->generateText();
            }, $template['replacement_array']);
        }
        $text = vsprintf($template['template'], $replacementArray);
        //print_r('OR___________' . "\n");
        //print_r($text . "\n");
        return $text;
    }

}
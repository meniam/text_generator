<?php

class TextGenerator_OrPart extends TextGenerator_Part
{
    private $templateArray = array();

    public function __construct($template)
    {
        $templateArray = preg_split('#\|#', $template);
        print_r($templateArray);
        for ($i = 0, $count = count($templateArray); $i < $count; $i++) {
            $this->templateArray[] = $this->parseTemplate($template);
        }
        //print_r($this->templateArray);
    }

    public function generateText()
    {
        $template = $this->templateArray[0];
        $replacementArray = array_map(function($value) {
            /** @var $value TextGenerator_Part */
            return $value->generateText();
        }, $template['replacement_array']);
        $text = vsprintf($template['template'], $replacementArray);
        print_r('OR___________' . "\n");
        print_r($text . "\n");
        return $text;
    }

}
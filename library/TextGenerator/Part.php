<?php

class TextGenerator_Part
{
    private $template = '';

    public function __construct($template)
    {
        $this->template = $this->parseTemplate($template);
    }

    public function parseTemplate($template)
    {
        $replacement = array();
        $template = preg_replace_callback('#(?:\[|\{)((?:(?:[^\[\{\]\}]+)|(?R))*)(?:\]|\})#', function($match) use (&$replacement) {
            $replacement[] = TextGenerator::factory($match[0]);
            return '%s';
        }, $template);

        return array(
            'template'    => $template,
            'replacement_array' => $replacement
        );
    }

    public function generateText()
    {
        $template = $this->template;
        $replacementArray = array_map(function($value) {
            /** @var $value TextGenerator_Part */
            return $value->generateText();
        }, $template['replacement_array']);
        $text = vsprintf($template['template'], $replacementArray);
        print_r('SIMPLE___________' . "\n");
        print_r($text . "\n");
        return $text;
    }
}
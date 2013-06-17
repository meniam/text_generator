<?php

require_once 'TextGenerator/Part.php';

class TextGenerator_XorPart extends TextGenerator_Part
{
    private $currentTemplateKey = 0;

    public function __construct($template)
    {
        $template             = $this->parseTemplate($template);
        $template['template'] = explode('|', $template['template']);
        $this->template       = $template;
    }

    public function nextCurrentTemplateKey()
    {
        $this->currentTemplateKey++;
    }

    public function getCurrentTemplate()
    {
        $templateArray = $this->template['template'];

        $templateKey = $this->currentTemplateKey;
        if (!isset($templateArray[$templateKey])) {
            $templateKey              = 0;
            $this->currentTemplateKey = 0;
        }
        //$this->currentTemplateKey = $templateKey + 1;

        return $templateArray[$templateKey];
    }
}
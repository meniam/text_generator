<?php

require_once 'TextGenerator/Part.php';

class TextGenerator_XorPart extends TextGenerator_Part
{
    /**
     * Массив шаблонов для генерации
     * @var array
     */
    protected $template;

    /**
     * Текущий ключ массива шаблонов
     * @var int
     */
    private $currentTemplateKey = 0;

    public function __construct($template)
    {
        $template = $this->parseTemplate($template);

        $this->template         = explode('|', $template['template']);
        $this->replacementArray = $template['replacement_array'];
    }

    /**
     * Смещает текущий ключ массива
     */
    public function next()
    {
        $this->currentTemplateKey++;
    }

    public function getCurrentTemplate()
    {
        $templateArray = $this->template;

        $templateKey = $this->currentTemplateKey;
        if (!isset($templateArray[$templateKey])) {
            $templateKey              = 0;
            $this->currentTemplateKey = 0;
        }
        //$this->currentTemplateKey = $templateKey + 1;

        return $templateArray[$templateKey];
    }
}
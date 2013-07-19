<?php

namespace TextGenerator;

class XorPart extends Part
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

    public function __construct($template, array $options = array())
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
        if (!isset($this->template[$this->currentTemplateKey])) {
            $this->currentTemplateKey = 0;
        }
    }

    /**
     * Returns current template value
     *
     * @return string
     */
    public function getCurrentTemplate()
    {
        $templateArray = $this->template;
        $templateKey = $this->currentTemplateKey;

        return $templateArray[$templateKey];
    }

    /**
     * @return string
     */
    protected function getRandomTemplate()
    {
        $templateArray = $this->template;
        $templateKey = mt_rand(0, count($this->template) - 1);
        return $templateArray[$templateKey];
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->template) + $this->getReplacementCount() - 1;
    }
}
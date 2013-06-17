<?php

class TextGenerator_Part
{
    /**
     * Шаблон для генерации, преобразованный в массив из шаблона и массива замен управляющих конструкций
     * @see TextGenerator_Part::parseTemplate()
     * @var array
     */
    protected $template;

    /**
     * @param string $template - шаблон, по которому будет генерироваться текст
     */
    public function __construct($template)
    {
        $this->template = $this->parseTemplate($template);
    }

    /**
     * Парсит шаблон, заменяет все управляющие конструкции (переборы, перестановки и т.д) и получает массив типа:
     * array(
     *   'template' => 'Генератор может генерировать %%0%%',
     *   'replacement_array' => array(
     *       '%%0%%' => TextGenerator_OrPart
     *    )
     * )
     *
     * @param string $template - шаблон
     *
     * @return array
     */
    public function parseTemplate($template)
    {
        $replacementArray = array();

        $template = preg_replace_callback('#(?:\[|\{)((?:(?:[^\[\{\]\}]+)|(?R))*)(?:\]|\})#', function ($match) use (&$replacementArray) {
            $key                    = '%%' . count($replacementArray) . '%%';
            $replacementArray[$key] = TextGenerator::factory($match[0]);
            return $key;
        }, $template);

        return array(
            'template'          => $template,
            'replacement_array' => $replacementArray
        );
    }

    /**
     * Сгенерировать текст по текущему шаблону
     *
     * @return string
     */
    public function generate()
    {
        $template         = $this->getCurrentTemplate();
        $replacementArray = $this->getReplacementArray();

        $replacementArrayTmp = array();
        $searchArray         = array();
        foreach ($replacementArray as $key => $value) {
            $searchArray[]         = $key;
            $replacementArrayTmp[] = $value->generate();
        }
        $replacementArray = $replacementArrayTmp;
        if ($searchArray) {
            return str_replace($searchArray, $replacementArray, $template);
        }
        return $template;
    }

    /**
     * Получить текущий шаблон, по которому будет сгенерен текст
     *
     * @return string
     */
    public function getCurrentTemplate()
    {
        return $this->template['template'];
    }

    /**
     * Получить массив замен для шаблона
     *
     * @return array
     */
    public function getReplacementArray()
    {
        return $this->template['replacement_array'];
    }
}
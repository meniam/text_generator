<?php

class TextGenerator_Part
{
    /**
     * Шаблон для генерации
     * @see TextGenerator_Part::parseTemplate()
     * @var string
     */
    protected $template;

    /**
     * Массив замен из управляющих конструкций (перестановок и переборов)
     * @var array
     */
    protected $replacementArray;

    /**
     * @param string $template - шаблон, по которому будет генерироваться текст
     */
    public function __construct($template)
    {
        $template               = $this->parseTemplate($template);
        $this->template         = $template['template'];
        $this->replacementArray = $template['replacement_array'];
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

        $this->next();

        if ($searchArray) {
            return str_replace($searchArray, $replacementArray, $template);
        }
        return $template;
    }

    protected function next()
    {
    }

    /**
     * Получить текущий шаблон, по которому будет сгенерен текст
     * @return string
     */
    public function getCurrentTemplate()
    {
        return $this->template;
    }

    /**
     * Получить массив замен для шаблона
     * @return array
     */
    public function getReplacementArray()
    {
        return $this->replacementArray;
    }
}
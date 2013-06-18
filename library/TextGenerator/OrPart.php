<?php
require_once 'TextGenerator/XorPart.php';

class TextGenerator_OrPart extends TextGenerator_XorPart
{
    /**
     * Разделитель между словами
     * @var string
     */
    private $delimiter = '';

    /**
     * Последовательность, в которой будут следовать фразы шаблона при генерации
     * @var array
     */
    private $currentTemplateKeySequence;

    /**
     * Массив последовательностей слов, из которых будут формироваться фразы
     * @var array
     */
    private $sequenceArray = array();

    public function __construct($template)
    {
        $delimiter       = '';
        $template        = preg_replace_callback('#^\+([^\+]+)\+#', function ($match) use (&$delimiter) {
            $delimiter = $match[1];
            return '';
        }, $template);
        $this->delimiter = $delimiter;

        parent::__construct($template);

        $firstSequence                    = range(0, count($this->template) - 1);
        $this->sequenceArray[0]        = $firstSequence;
        $this->currentTemplateKeySequence = $firstSequence;
    }

    /**
     * Получает последовательность из другой последовательности, например, из 012 получается 021, далее 102 и т.д
     * @param array $currentSequence - последовательность, на основе которой будет строится следующая
     *
     * @return mixed
     */
    public function getNextSequence($currentSequence)
    {
        $sequenceLength = count($currentSequence);

        $k = null;
        for ($i = 0; $i < $sequenceLength; $i++) {
            if (isset($currentSequence[$i + 1]) && $currentSequence[$i] < $currentSequence[$i + 1]) {
                $k = $i;
            }
        }
        //print_r($k);
        if (is_null($k)) {
            //На колу мочало, начинай с начала!
            return reset($this->sequenceArray);
        }
        $l = null;
        for ($i = 0; $i < $sequenceLength; $i++) {
            if ($currentSequence[$k] < $currentSequence[$i]) {
                $l = $i;
            }
            //echo $l;
        }
        //print_r($l);
        if (is_null($l)) {
            //На колу мочало, начинай с начала!
            return reset($this->sequenceArray);
        }
        $nextSequence     = $currentSequence;
        $nextSequence[$k] = $currentSequence[$l];
        $nextSequence[$l] = $currentSequence[$k];
        $k2               = $k + 1;
        //print_r($k2);
        /*for ($i = 0, $count = floor($sequenceLength / 2); $i < $count; $i++) {
            $key1 = $k2 + $i;
            $key2 = $k2 + $count - $i;
            if ($key1 <= $key2) {
                break;
            }
            print_r('////');
            print_r($key1);
            print_r($key2);
            print_r('////');
            $val1 = $nextSequence[$key1];
            $nextSequence[$key1] = $nextSequence[$key2];
            $nextSequence[$key2] = $val1;
        }*/
        //print_r($nextSequence);
        //print_r($this->getNextSequence($nextSequence));
        //die;
        $reversePart = array_slice($nextSequence, $k2);
        $reversePart = array_reverse($reversePart);
        array_splice($nextSequence, $k2, count($nextSequence), $reversePart);
        /*        print_r($this->getNextSequence($nextSequence));
                die;*/

        return $nextSequence;
    }

    /**
     * Смещает текущую последрвательность ключей массива шаблона на следующую
     */
    public function next()
    {
        //print_r($this->sequenceArray);
        $key = implode('', $this->currentTemplateKeySequence);
        if (!isset($this->sequenceArray[$key]) || !($nextSequence = $this->sequenceArray[$key])) {
            $nextSequence              = $this->getNextSequence($this->currentTemplateKeySequence);
            $this->sequenceArray[$key] = $nextSequence;
        }
        $this->currentTemplateKeySequence = $nextSequence;
    }

    public function getCurrentTemplate()
    {
        $templateKeySequence = $this->currentTemplateKeySequence;

        $templateArray = $this->template;
        for ($i = 0, $count = count($templateKeySequence); $i < $count; $i++) {
            $templateKey             = $templateKeySequence[$i];
            $templateKeySequence[$i] = $templateArray[$templateKey];
        }

        return implode($this->delimiter, $templateKeySequence);
    }
}
<?php
require_once 'TextGenerator/XorPart.php';

class TextGenerator_OrPart extends TextGenerator_XorPart
{
    private $delimiter = '';

    private $currentSequence;

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
    }

    /**
     * @return array
     */
    public function getCurrentSequence()
    {
        return $this->currentSequence;
    }

    public function getNextSequence()
    {
        if (!$this->currentSequence) {
            $this->currentSequence = range(0, count($this->template['template']) - 1);
            return $this->currentSequence;
        }
        $currentSequence = $this->currentSequence;

        $count = count($currentSequence);
        $k     = null;
        for ($i = 0; $i < $count; $i++) {
            if (isset($currentSequence[$i + 1]) && $currentSequence[$i] < $currentSequence[$i + 1]) {
                $k = $i;
            }
        }
        if (is_null($k)) {
            //На колу мочало, начинай с начала!
            return range(0, count($this->template['template']) - 1);
        }
        $l = null;
        for ($i = 0; $i < $count; $i++) {
            if ($currentSequence[$k] < $currentSequence[$i]) {
                $l = $i;
            }
            //echo $l;
        }
        if (is_null($l)) {
            //На колу мочало, начинай с начала!
            return range(0, count($this->template['template']) - 1);
        }
        $k2               = $k + 1;
        $nextSequence     = $currentSequence;
        $nextSequence[$k] = $currentSequence[$l];
        $nextSequence[$l] = $currentSequence[$k];

        $reversePart = array_slice($nextSequence, $k2);
        $reversePart = array_reverse($reversePart);
        array_splice($nextSequence, $k2, count($nextSequence), $reversePart);

        return $nextSequence;
    }

    public function getCurrentTemplate()
    {
        $templateSequence      = $this->getNextSequence();
        $this->currentSequence = $templateSequence;

        $templateArray = $this->template['template'];
        for ($i = 0, $count = count($templateSequence); $i < $count; $i++) {
            $templateKey          = $templateSequence[$i];
            $templateSequence[$i] = $templateArray[$templateKey];
        }

        return implode($this->delimiter, $templateSequence);
    }
}
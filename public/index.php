<?php

define('PROJECT_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('LIBRARY_PATH', realpath(PROJECT_PATH . DIRECTORY_SEPARATOR . 'library'));

set_include_path(
    get_include_path() .
    PATH_SEPARATOR . LIBRARY_PATH
);

require_once 'TextGenerator.php';

$template = 'SeoGenerator { PRO|} { - |:} {программа, предназначенная|программный продукт, предназначенный} для {генерации|создания} уникальных [+,+описаний сайтов|названий сайтов|tt{анкоров|текстов ссылок}].Поддерживаются [+,+[+ и +переборы|перестановки]|вложенный синтаксис].';

$t = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $generator = TextGenerator::factory($template);
    $generator->generateText();
}
$generator = TextGenerator::factory($template);
echo $generator->generateText();

echo '<br />------------------<br />';
echo microtime(true) - $t;
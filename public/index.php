<?php

define('PROJECT_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('LIBRARY_PATH', realpath(PROJECT_PATH . DIRECTORY_SEPARATOR . 'library'));

set_include_path(
    get_include_path() .
    PATH_SEPARATOR . LIBRARY_PATH
);

require_once 'TextGenerator.php';

$template = 'SeoGenerator {PRO|} {-|:} {программа, предназначенная|программный продукт, предназначенный} для {генерации|создания} уникальных [ +, +описаний сайтов|названий сайтов|{анкоров|текстов ссылок}]. Поддерживаются [+, +[+ и +переборы|перестановки]|вложенный синтаксис|прочее].';

$t = microtime(true);
$generator = TextGenerator::factory($template);
for ($i = 0; $i < 500; $i++) {
    echo '<br /><br />';
    echo $generator->generate();
}
$generator = TextGenerator::factory($template);
echo $generator->generate();

echo '<br />------------------<br />';
echo microtime(true) - $t;
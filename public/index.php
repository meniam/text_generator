<?php

define('PROJECT_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('LIBRARY_PATH', realpath(PROJECT_PATH . DIRECTORY_SEPARATOR . 'library'));

set_include_path(
    get_include_path() .
    PATH_SEPARATOR . LIBRARY_PATH
);
?>

<form action="" method="POST">
    <textarea name="template" id="template" cols="100" rows="10">Генератор текста{ -|:} {скрипт, предназначенный|программа, предназначенная} для [+ и +генерации|создания] уникальных [ +, +описаний|названий|{анкоров|ссылок}].</textarea>
    <br>
    <input type="submit" value="Генерить!" />
</form>

<?php
if (isset($_POST['template'])) {
    $template = $_POST['template'];

    require_once 'TextGenerator.php';

    $t = microtime(true);
    $generator = TextGenerator::factory($template);
    for ($i = 0; $i < 50; $i++) {
        echo '<br /><br />';
        echo $generator->generate();
    }

    echo '<br />------------------<br />';
    echo microtime(true) - $t;
}
?>



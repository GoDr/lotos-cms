<?php
/**
 * @package      GDFeedback
 * @version      1.1
 * @author       Gold Dragon & Lotos CMS <support@lotos-cms.ru>
 * @link         http://lotos-cms.ru
 * @date         24.10.2013
 * @modification 12.11.2013 Gold Dragon
 * @copyright    Авторские права (C) 2000-2013 Gold Dragon.
 * @license      The MIT License (MIT)
 *               Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description  GDFeedback - модуль обрратной связи
 * @see          http://wiki.lotos-cms.ru/index.php/GDFeedback
 */

$C_IMAGE_TYPE = 'PNG';
$C_WIDTH = 150;
$C_HEIGHT = 50;
$C_NUM_GENSIGN = 5;
$path_fonts = __DIR__ . '/fonts/';

session_start();

$C_FONT_SIZE = intval($C_HEIGHT / (($C_HEIGHT / $C_WIDTH) * 7));
$C_NUM_SIGN = intval(($C_WIDTH * $C_HEIGHT) / 150);

$CODE = array();
$LETTERS = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c', 'd', 'e', 'f');
$FIGURES = array('50', '70', '90', '110', '130', '150', '170', '190', '210');

//Создаем полотно
$src = imagecreatetruecolor($C_WIDTH, $C_HEIGHT);

//Заливаем фон
$fon = imagecolorallocate($src, 255, 255, 255);
imagefill($src, 0, 0, $fon);

//Загрузка шрифтов
$FONTS = array();
$dir = opendir($path_fonts);
while ($fontName = readdir($dir)) {
    if ($fontName != "." && $fontName != "..") {
        if (strtolower(strrchr($fontName, '.')) == '.ttf') {
            $FONTS[] = $path_fonts . $fontName;
        }
    }
}
closedir($dir);

//Если есть шрифты
if (sizeof($FONTS) > 0) {
    //Заливаем полотно буковками
    for ($i = 0; $i < $C_NUM_SIGN; $i++) {
        $h = 1;
        $color = imagecolorallocatealpha($src, rand(0, 255), rand(0, 255), rand(0, 255), 105);
        $font = $FONTS[rand(0, sizeof($FONTS) - 1)];
        $letter = $LETTERS[rand(0, sizeof($LETTERS) - 1)];
        $size = rand($C_FONT_SIZE - 2, $C_FONT_SIZE + 2);
        $angle = rand(0, 60);
        if ($h == rand(1, 2)) {
            $angle = rand(360, 300);
        }
        //Пишем
        imagettftext($src, $size, $angle, rand($C_WIDTH * 0.1, $C_WIDTH - $C_WIDTH * 0.1), rand($C_HEIGHT * 0.2, $C_HEIGHT), $color, $font, $letter);
    }

    //Заливаем основными буковками
    for ($i = 0; $i < $C_NUM_GENSIGN; $i++) {
        //Ориентир
        $h = 1;
        //Рисуем
        $color = imagecolorallocatealpha($src, $FIGURES[rand(0, sizeof($FIGURES) - 1)], $FIGURES[rand(0, sizeof($FIGURES) - 1)], $FIGURES[rand(0, sizeof($FIGURES) - 1)], rand(10, 30));
        $font = $FONTS[rand(0, sizeof($FONTS) - 1)];
        $letter = $LETTERS[rand(0, sizeof($LETTERS) - 1)];
        $size = rand($C_FONT_SIZE * 2.1 - 1, $C_FONT_SIZE * 2.1 + 1);
        $x = (empty($x)) ? $C_WIDTH * 0.08 : $x + ($C_WIDTH * 0.8) / $C_NUM_GENSIGN + rand(0, $C_WIDTH * 0.01);
        $y = ($h == rand(1, 2)) ? (($C_HEIGHT * 1.15 * 3) / 4) + rand(0, $C_HEIGHT * 0.02) : (($C_HEIGHT * 1.15 * 3) / 4) - rand(0, $C_HEIGHT * 0.02);
        $angle = rand(5, 20);
        //Запоминаем
        $CODE[] = $letter;
        if ($h == rand(0, 10)) {
            $letter = strtoupper($letter);
        }
        if ($h == rand(1, 2)) {
            $angle = rand(355, 340);
        }
        //Пишем
        imagettftext($src, $size, $angle, $x, $y, $color, $font, $letter);
    }

    //Если нет шрифтов
} else {
    //Заливаем точками
    for ($x = 0; $x < $C_WIDTH; $x++) {
        for ($i = 0; $i < ($C_HEIGHT * $C_WIDTH) / 1000; $i++) {
            $color = imagecolorallocatealpha($src, $FIGURES[rand(0, sizeof($FIGURES) - 1)], $FIGURES[rand(0, sizeof($FIGURES) - 1)], $FIGURES[rand(0, sizeof($FIGURES) - 1)], rand(10, 30));
            imagesetpixel($src, rand(0, $C_WIDTH), rand(0, $C_HEIGHT), $color);
        }
    }
    unset($x, $y);
    //Заливаем основными буковками
    for ($i = 0; $i < $C_NUM_GENSIGN; $i++) {
        //Ориентир
        $h = 1;
        //Рисуем
        $color = imagecolorallocatealpha($src, $FIGURES[rand(0, sizeof($FIGURES) - 1)], $FIGURES[rand(0, sizeof($FIGURES) - 1)], $FIGURES[rand(0, sizeof($FIGURES) - 1)], rand(10, 30));
        $letter = $LETTERS[rand(0, sizeof($LETTERS) - 1)];
        $x = (empty($x)) ? $C_WIDTH * 0.08 : $x + ($C_WIDTH * 0.8) / $C_NUM_GENSIGN + rand(0, $C_WIDTH * 0.01);
        $y = ($h == rand(1, 2)) ? (($C_HEIGHT * 1) / 4) + rand(0, $C_HEIGHT * 0.1) : (($C_HEIGHT * 1) / 4) - rand(0, $C_HEIGHT * 0.1);
        //Запоминаем
        $CODE[] = $letter;
        if ($h == rand(0, 10)) {
            $letter = strtoupper($letter);
        }
        //Пишем
        imagestring($src, 5, $x, $y, $letter, $color);
    }
}

//Получаем код
$_SESSION['securityCode'] = implode('', $CODE);

//Печать
if ($C_IMAGE_TYPE == 'PNG') {
    header("Content-type: image/png");
    imagepng($src, "../../cache/code_image.png");
} elseif ($C_IMAGE_TYPE == 'JPEG') {
    header("Content-type: image/jpeg");
    imagejpeg($src);
} else {
    header("Content-type: image/gif");
    imagegif($src);
}
imagedestroy($src);

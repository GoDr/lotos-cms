<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package      GDSlider Modul
 * @version      1.1
 * @author       Gold Dragon & JLotos CMS <support@jlotos.ru>
 * @link         http://jlotos.ru
 * @date         10.10.2013
 * @copyright    Авторские права (C) 2000-2013 Gold Dragon.
 * @license      The MIT License (MIT)
 *               Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description  GDSlider - модуль слайшоу
 * @see          http://wiki.lotos-cms.ru/index.php/GDSlider
 */

class mod_gdslider_Helper
{
    /** @var string CSS-суффикс класса модуля */
    private $moduleclass_sfx;

    /** @var string Путь до директории с изображениями */
    private $image_path;

    /** @var  string Преффикс имени файла */
    private $image_pref;

    /** @var  int Количество изображений для выборки */
    private $image_count;

    /** @var  int Скорость смены изображений */
    private $speed;

    /** @var  int Время задержки */
    private $timeout;

    /** @var  string Эффект смены изображений */
    private $fx_cycle;

    /** @var  int Ширина основного изображения */
    private $image_big_width;

    /** @var  int Высота основного изображения */
    private $image_big_height;

    /** @var  string Эффект появления панели */
    private $fx_easing_in;

    /** @var  int Скорость появления панели */
    private $speed_in;

    /** @var  int Задержка появления */
    private $pause_in;

    /** @var  string Эффект скрытия панели */
    private $fx_easing_out;

    /** @var  int Скорость скрытия панели */
    private $speed_out;

    /** @var  int Задержка скрытия */
    private $pause_out;

    /** @var  int Ширина изображений предпросмотра */
    private $image_small_width;

    /** @var  int Высота изображений предпросмотра */
    private $image_small_height;

    /** @var  int Качество изображений предпросмотра */
    private $image_small_quality;

    /** @var  int Идентификатор модуля */
    private $modul_id;

    /**
     * Получение настроек  модуля
     *
     * @param object $params   : Параметры модуля
     * @param int    $moduleid : Идентификатор модуля
     *
     * @data 21.10.2013
     */
    private function getParams($params, $moduleid)
    {
        $this->moduleclass_sfx = trim($params->get('moduleclass_sfx', ''));

        $this->image_path = trim($params->get('image_path', ''));
        $this->image_path = preg_replace("#(^[\s]*[\/]*|[\/]*[\s]*$)#", '', $this->image_path);

        $this->image_pref = trim($params->get('image_pref', 'gds_'));
        $this->image_count = intval($params->get('image_count', 5));
        $this->speed = intval($params->get('speed', 600));
        $this->timeout = intval($params->get('timeout', 3000));

        $_tmp = intval($params->get('fx_cycle', 0));
        $_fx_cycle = array(
            0  => 'none',
            1  => 'blindX',
            2  => 'blindY',
            3  => 'blindZ',
            4  => 'cover',
            5  => 'curtainX',
            6  => 'curtainY',
            7  => 'fade',
            8  => 'fadeZoom',
            9  => 'growX',
            10 => 'growY',
            11 => 'scrollUp',
            12 => 'scrollDown',
            13 => 'scrollLeft',
            14 => 'scrollRight',
            15 => 'scrollHorz',
            16 => 'scrollVert',
            17 => 'shuffle',
            18 => 'slideX',
            19 => 'slideY',
            20 => 'toss',
            21 => 'turnUp',
            22 => 'turnDown',
            23 => 'turnLeft',
            24 => 'turnRight',
            25 => 'uncover',
            26 => 'wipe',
            27 => 'zoom'
        );
        if ($_tmp > 27) {
            array_shift($_fx_cycle);
            $this->fx_cycle = $_fx_cycle[array_rand($_fx_cycle)];
        } else {
            $this->fx_cycle = $_fx_cycle[$_tmp];
        }

        $this->image_big_width = intval($params->get('image_big_width', 960));
        $this->image_big_height = intval($params->get('image_big_height', 400));
        $this->fx_easing_in = trim($params->get('fx_easing_in', 'easeOutBack'));
        $this->speed_in = intval($params->get('speed_in', 600));
        $this->pause_in = intval($params->get('pause_in', 100));
        $this->fx_easing_out = trim($params->get('fx_easing_out', 'easeOutBack'));
        $this->speed_out = intval($params->get('speed_out', 300));
        $this->pause_out = intval($params->get('pause_out', 11));
        $this->image_small_width = intval($params->get('image_small_width', 90));
        $this->image_small_height = intval($params->get('image_small_height', 70));
        $this->image_small_quality = intval($params->get('image_small_quality', 75));
        $this->modul_id = $moduleid;

    }

    /**
     * Функция выводи модуль
     * @param object $params   : Параметры модуля
     * @param int    $moduleid : Идентификатор модуля
     *
     * @data 21.10.2013
     */
    public function getHTML($params, $moduleid = 0)
    {
        // получение параметров модуля
        $this->getParams($params, $moduleid);

        // получаем все изображения
        $images_array = glob(_LPATH_ROOT . '/' . $this->image_path . '/' . $this->image_pref . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        // мешаем массив
        shuffle($images_array);

        // получаем нужное количество изображений с путями
        $images_big = array();
        $images_small = array();
        for ($i = 0; $i < $this->image_count; $i++) {
            if (!isset($images_array[$i])) {
                break;
            }
            // Большая картинка
            $_image = str_replace(_LPATH_ROOT, _LPATH_SITE, $images_array[$i]);
            $images_big[$i] = '<img src="' . $_image . '" width="' . $this->image_big_width . '" height="' . $this->image_big_height . '">';

            // Маленькая картинка
            $src = _LPATH_SITE . '/modules/mod_gdslider/imgsketch.php?'
                . 'src=' . $_image
                . '&w=' . $this->image_small_width
                . '&h=' . $this->image_small_height
                . '&q=' . $this->image_small_quality;
            $images_small[$i] = '<li><a><img src="' . $src . '" width="' . $this->image_small_width . '" height="' . $this->image_small_height . '" /></a></li>';
        }

        LHtml::loadJqueryPlugins('jquery.cycle', true);
        LHtml::loadJqueryPlugins('jquery.easing', true);

        ?>
        <div id="siteContainer_gds_<?php echo $this->modul_id; ?>">
            <div id="homeSlides_gds_<?php echo $this->modul_id; ?>">
                <div id="slides_gds_<?php echo $this->modul_id; ?>">
                    <?php echo implode("\n", $images_big); ?>
                </div>
                <ul id="slidesNav_gds_<?php echo $this->modul_id; ?>">
                    <?php echo implode("\n", $images_small); ?>
                </ul>
            </div>
        </div>
        <script>
            jQuery(function () {
                $("#slides_gds_<?php echo $this->modul_id; ?>").cycle({
                    fx: "<?php echo $this->fx_cycle; ?>",
                    speed: <?php echo $this->speed; ?>,
                    timeout: <?php echo $this->timeout; ?>,
                    pager: "#slidesNav_gds_<?php echo $this->modul_id; ?>",
                    pagerAnchorBuilder: function (a) {
                        return"#slidesNav_gds_<?php echo $this->modul_id; ?> li:eq(" + a + ") a"
                    }
                });
                $("#homeSlides_gds_<?php echo $this->modul_id; ?>").hover(function () {
                    var a = <?php echo $this->speed_in; ?>;
                    $("#slidesNav_gds_<?php echo $this->modul_id; ?> li a").each(function () {
                        $_tmp = <?php echo $this->image_small_height; ?> +30;
                        $(this).stop().animate({top: "-" + $_tmp + "px"}, a, "<?php echo $this->fx_easing_in; ?>");
                        a += <?php echo $this->pause_in; ?>;
                    })
                }, function () {
                    var a = <?php echo $this->speed_out; ?>;
                    $("#slidesNav_gds_<?php echo $this->modul_id; ?> li a").each(function () {
                        $(this).stop().animate({top: "0"}, a, "<?php echo $this->fx_easing_out; ?>");
                        a += <?php echo $this->pause_out; ?>;
                    })
                });
                $("#slidesNav_gds_<?php echo $this->modul_id; ?> li a img").hover(function () {
                    $_tmp1 = <?php echo $this->image_small_width; ?> +20;
                    $_tmp2 = <?php echo $this->image_small_height; ?> +12;
                    $(this).stop().animate({width: $_tmp1 + "px", height: $_tmp2 + "px", top: "-10px", left: "-10px"}, 150)
                }, function () {
                    $(this).stop().animate({width: "<?php echo $this->image_small_width; ?>px", height: "<?php echo $this->image_small_height; ?>px", top: "0", left: "0"}, 150)
                });
            });
        </script>
    <?php
    }
}


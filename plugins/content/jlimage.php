<?php defined('_LINDEX') or die();

/**
 * @package   JLImage - Замена изображений в контенте "всплывающими" изображениями, увеличивающимися при нажатии
 * @copyright Авторские права (C) 2000-2014 Gold Dragon.
 * @license   The MIT
 *            GDNLotos - Главные новости - модуль позволяет выводить основные материалы по определённым критериям
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл view/copyright.php.
 */

LHtml::loadJqueryPlugins('fancybox/jquery.fancybox', true, true);

$_PLUGINS->registerFunction('onPrepareContent', 'botJLImage');

/**
 * @param $published
 * @param $row
 * @param $params
 *
 * @modification : 03.04.2014 Gold dragon
 */
function botJLImage($published, $row, $params)
{
    static $bb = 0;
    // Включен ли плагин
    if ($published) {
        // Есть ли TEXT, нет ли заглушки {{jlimage}}, есть ли data-plugin-core
        if (isset($row->text) and stripos($row->text, '{{jlimage}}') === false) {

            // Получаем каталог
            $directory = LCore::getParam($_REQUEST, 'directory', 0, 'i');
            if (!$directory) {
                $mainframe = MainFrame::getInstance();

                require_once($mainframe->getPath('class', 'com_frontpage'));
                $configObject = new frontpageConfig();

                $database = database::getInstance();
                $sql = "SELECT `value` FROM `#__config` WHERE `name` = 'directory' AND `group` = 'com_frontpage' AND `subgroup` = 'default'";
                $database->setQuery($sql);
                $directory = $configObject->_parseValue($database->loadResult());
            }

            // Получаем параметры
            $_PLUGINS = mosPluginHandler::getInstance();
            $plugin = $_PLUGINS->_content_plugin_params['jlimage'];

            $botParams = new mosParameters($plugin->params);
            $param['directory'] = intval($botParams->def('directory', 0));
            $param['catid'] = $botParams->def('catid', '');
            $catids = ($param['catid']) ? $catids = explode(',', $param['catid']) : array();
            $param['width'] = intval($botParams->def('size', 200));
            $param['quality'] = intval($botParams->def('quality', 3));
            if ($param['quality'] > 9) {
                $param['quality'] = 3;
            }
            $param['style'] = $botParams->def('style', 0);
            $param['style_default'] = $botParams->def('style_default', 'left');
            $param['correct'] = $botParams->def('correct', 1);
            $param['ignor_small'] = $botParams->def('ignor_small', 1);

            $param['overlay_opacity'] = $botParams->def('overlay_opacity', '0.3');
            $param['overlay_color'] = trim($botParams->def('overlay_color', '#000000'));
            $param['overlay_color'] = (preg_match('/^#([0-9a-f]{6}|[0-9a-f]{3})$/', $param['overlay_color'])) ? $param['overlay_color'] : '#000000';
            $param['transition_in'] = $botParams->def('transition_in', 'elastic');
            $param['transition_out'] = $botParams->def('transition_out', 'elastic');
            $param['speed_in'] = intval($botParams->def('speed_in', 600));
            $param['speed_out'] = intval($botParams->def('speed_out', 600));

            if (isset($row->catid) and ($param['directory'] == $directory or $param['directory'] == 0) and (in_array($row->catid, $catids) or $param['catid'] == '')) {

                // получаем все картинки
                $b = preg_match_all('#<img[^>]*src=(["\'])([^"\']*)\1[^>]*>#is', $row->text, $preg_result, PREG_SET_ORDER);

                // Есть ли картинки
                if ($b) {
                    $image_old = array();
                    $image_new = array();
                    $script = array();

                    // перебираем картинки
                    foreach ($preg_result as $value) {

                        // Проверка data-plugin-core
                        $b = preg_match('#data-plugin-core=[\s\'"]?(\d)[\s\'"]?#ius', $value[0], $tmp);

                        if (!$b or ($b and $tmp[1])) {
                            if (jlimage_check_url($value[2])) {
                                // Проверяем где находится картинка (внешняя или с сайта
                                $parse_url = parse_url($value[2]);
                                $parse_site = parse_url(_LPATH_SITE);

                                // Если картинка с этого сайта
                                if ((isset($parse_url['host']) and $parse_url['host'] == $parse_site['host']) or !isset($parse_url['host'])) {
                                    if (is_readable(_LPATH_ROOT . $parse_url['path'])) {
                                        $value[2] = _LPATH_SITE . $parse_url['path'];
                                        $info_image = getimagesize($value[2]);
                                    } else {
                                        $info_image = false;
                                    }
                                } else {
                                    $info_image = getimagesize($value[2]);
                                }

                                // Готовим картинки
                                if ($info_image) {

                                    // Готовим размеры
                                    if ($info_image[0] > $param['width']) {
                                        $img_width = $param['width'];
                                        $img_height = intval($img_width * $info_image[1] / $info_image[0]);
                                    } else {
                                        $img_width = $info_image[0];
                                        $img_height = $info_image[1];
                                    }
                                    // готовим выравнивание
                                    if ($param['style']) {
                                        $img_float = 'float:' . $param['style'] . ';';
                                    } else {
                                        // берём данные из стиля
                                        $img_float = (preg_match('#style=["\'].*?float\s*:\s*(left|right|none|inherit)#si', $value[0], $temp)) ? 'float:' . trim($temp[1]) . ';' : '';
                                        // При пустом значении пытаемся достать данные из align
                                        if ($img_float == '') {
                                            $img_float = (preg_match('#align=["\']\s*(left|right|none|inherit)#si', $value[0], $temp)) ? 'float:' . trim($temp[1]) . ';' : '';
                                        }
                                        // Попытка применить стиль по умолчанию
                                        if ($img_float == '' and $param['style_default']) {
                                            $img_float = 'float:' . $param['style_default'] . ';';
                                        }
                                    }

                                    // Готовим альтернативный текст - ALT
                                    $b = preg_match('#alt=["\'](.*?)["\']#su', $value[0], $temp);
                                    $img_alt = ($b) ? $temp[1] : '';

                                    // Готовим описание - TITLE
                                    $b = preg_match('#title=["\'](.*?)["\']#su', $value[0], $temp);
                                    $img_title = ($b) ? $temp[1] : '';

                                    // коррекция окончания файла изображения
                                    if ($param['correct']) {
                                        // прооверяем а есть ли окончание у изображение (например, ссылки с fotki.yandex.ru не  имеют окончания)
                                        $b = preg_match('#(\.jpeg|\.jpg|\.gif|\.png)$#i', $value[2]);
                                        if (!$b) {
                                            if ($info_image['mime'] == 'image/gif') {
                                                $value[2] = $value[2] . '.gif';
                                            } elseif ($info_image['mime'] == 'image/png') {
                                                $value[2] = $value[2] . '.png';
                                            } else {
                                                $value[2] = $value[2] . '.jpg';
                                            }
                                        }
                                    }

                                    // Готовим картинку
                                    if ($info_image[0] > $param['width'] or !$param['ignor_small']) {
                                        $src = _LPATH_SITE . '/plugins/content/plugin_jlimage/imgsketch.php?' . 'src=' . $value[2] . '&w=' . $img_width . '&h=' . $img_height . '&q=' . $param['quality'];

                                        // Формируем картинку
                                        $image_new[]
                                            = '
                            <a id="plgjl-' . $row->catid . '-' . $row->id . '-' . $bb . '" href="' . $value[2] . '">
                            <img alt="'.$img_alt.'" title="'.$img_title.'" src="' . $src . '" width="' . $img_width . '" height="' . $img_height . '" style="' . $img_float . '" />
                            </a>
                            ';
                                        $script[]
                                            = '
                                    $(function(){
                                    $("#plgjl-' . $row->catid . '-' . $row->id . '-' . $bb . '")
							    .fancybox({
							        "overlayShow":true,
							        "overlayOpacity":' . $param['overlay_opacity'] . ',
							        "transitionIn":"' . $param['transition_in'] . '",
							        "transitionOut":"' . $param['transition_out'] . '",
							        "overlayColor":"' . $param['overlay_color'] . '",
							        "speedIn":"' . $param['speed_in'] . '",
							        "speedOut":"' . $param['speed_out'] . '"
							    });
							    });';
                                    } else {
                                        $image_new[] = ' <img src="' . $value[2] . '" width="' . $img_width . '" height="' . $img_height . '" style="' . $img_float . '" />';
                                    }
                                    $bb++;
                                } else {
                                    // если нет картинки или в ней ошибка то просто очищаем
                                    $image_new[] = '';
                                }
                                // Запоминаем оригинальный тэг IMG
                                $image_old[] = $value[0];
                            } else {
                                $image_new[] = '';
                                $image_old[] = $value[0];
                            }
                        }else{
                            $image_new[] = $value[0];
                            $image_old[] = $value[0];
                        }

                        // Формируем окончательно скрипт
                        $script_all = (count($script)) ? '<script>' . implode($script) . '</script>' : '';

                        // Заменяем оригиналы картинок на превьюшки
                        $row->text = str_replace($image_old, $image_new, $row->text) . $script_all;
                    }
                }
            }
        }
    }
    // удаляем из контента {{jlimage}}
    $row->text = str_replace('{{jlimage}}', '', $row->text);
}

/**
 * Проверка на доступность URL
 *
 * @param $url : URL
 *
 * @return bool: true - доступен, false - недоступен
 *
 * @modification 30.11.2013 Gold dragon
 */
function jlimage_check_url($url)
{
    if (!preg_match("#^http:#", $url)) {
        if ($url[0] != '/') {
            $url = '/' . $url;
        }
        $url = _LPATH_SITE . $url;
    }
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_HEADER, 1); // читать заголовок
    curl_setopt($c, CURLOPT_NOBODY, 1); // читать ТОЛЬКО заголовок без тела
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // не использовать cache
    if (!curl_exec($c)) {
        return false;
    }

    $httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
    return ($httpcode < 400);
}






















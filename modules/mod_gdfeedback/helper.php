<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package      GDFeedback
 * @version      1.0
 * @author       Gold Dragon & Lotos CMS <support@lotos-cms.ru>
 * @link         http://lotos-cms.ru
 * @date         24.10.2013
 * @copyright    Авторские права (C) 2000-2013 Gold Dragon.
 * @license      The MIT License (MIT)
 *               Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description  GDFeedback - модуль обрратной связи
 * @see          http://wiki.lotos-cms.ru/index.php/GDFeedback
 */

class mod_gdfeedback_Helper
{
    /** @var string CSS-суффикс класса модуля */
    private $moduleclass_sfx;

    /** @var string Название формы */
    private $name_form;

    /** @var string Email: кому */
    private $email_to;

    /** @var string Email: копия */
    private $email_c;

    /** @var string Email: скрытая копия */
    private $email_cc;

    /** @var int Показывать CAPTCHA */
    private $captcha;

    /** @var string Шаблон письма */
    private $template;

    /** @var string Элементы  формы */
    private $form;

    /** @var array массив с ошибками */
    private $error = array();

    /** @var int выводить ли ошибки */
    private $error_view;

    /** @var array значения для замены */
    private $fields = array();

    /** @var string идентификатор модуля */
    private $moduleid;

    /**
     * Функция выводи модуль
     * @param object $params   : Параметры модуля
     * @param int    $moduleid : Идентификатор модуля
     *
     * @return bool : false - ошибка, true - ок
     *
     * @modification 12.11.2013 Gold Dragon
     */
    public function getHTML($params, $moduleid = 0)
    {
        // получение параметров модуля
        $this->getParams($params, $moduleid);

        // если есть ошибки, то выводим их
        if ($this->getError()) {
            return false;
        }

        // массив будет хранить ключи
        $key_array = array();

        foreach ($this->form as $key => $value) {

            // проверяем правильность ключа
            if (strpos($key, ':')) {

                // Поручаем тип поля и его имя
                $_key = explode(':', $key);

                // Получаем поле
                $_func = 'field_' . $_key[0];
                $key_array[$_key[1]] = (isset($value['desc'])) ? $value['desc'] : '';
                $this->$_func($_key[1], $value);

                // если есть ошибки, то выводим их
                if ($this->getError()) {
                    return false;
                }
            }
        }

        $_lang = LLang::getLang('mod.gdfeedback');

        // Добавляем основные поля для замены
        $this->fields['[error]'] = '<div class="feedback_itog"></div>';
        $this->fields['[code-txt]'] = ($this->captcha) ? $_lang['MOD_GDFB_CODE_TEXT'] : '';
        $this->fields['[code-img]'] = ($this->captcha) ? '<div id="img_code_' . $this->moduleid . '"></div>
                    <input id="code" maxlength="6" size="4" type="text" name="code">
                    <a class="ref" onclick="gdfeedback_code_change(' . $this->moduleid . ')">' . $_lang['MOD_GDFB_CODE_TEXT_A'] . '</a>' : '';
        $this->fields['[buttom]'] = '<input type="submit" name="feedback_' . $this->moduleid . '" value="' . $_lang['MOD_GDFB_NAME_SUBMIT'] . '"/>';

        // Вставляем данные в шаблон
        $_form = strtr($this->template, $this->fields);

        // проверяем остались ли незадействованные поля
        if (preg_match_all('#\[[\w]+-[dk]+\]#isu', $_form, $matches)) {
            $this->error[] = $_lang['MOD_GDFB_ERR_FORM_FIELD'] . '<br />' . implode('<br />', $matches[0]);

            // если есть ошибки, то выводим их
            if ($this->getError()) {
                return false;
            }
        }
        LHtml::addJS(_LPATH_SITE . '/includes/js/jquery/plugins/arcticmodal/jquery.arcticmodal.js', '', true);
        LHtml::addCSS(_LPATH_SITE . '/includes/js/jquery/plugins/arcticmodal/jquery.arcticmodal.css',  true);
        LHtml::addJS(_LPATH_SITE . '/modules/mod_gdfeedback/work_data.js', '', true);

        ?>
        <div>
            <a class="feedback_m" id="feedback_a_<?php echo $this->moduleid; ?>"><?php echo $this->name_form; ?></a>
        </div>
        <div class="feedback_g">
            <div class="feedback_box" id="feedback_box_<?php echo $this->moduleid; ?>">
                <div class="feedback_box_close arcticmodal-close">закрыть</div>
                <form id="feedback_<?php echo $this->moduleid; ?>">
                    <?php echo $_form; ?>
                </form>
                <div class="feedback_box_ok"></div>
            </div>
        </div>
        <script>
            $(function () {
                gdfeedback_code_change(<?php echo $this->moduleid; ?>);

                $("#feedback_a_<?php echo $this->moduleid; ?>").bind('click', function () {
                    $('#feedback_box_<?php echo $this->moduleid; ?>').arcticmodal({
                        afterClose: function () {
                            $(".feedback_itog").html('');
                            $(".feedback_box_ok").html('');
                            $(".feedback_box form").show();
                            gdfeedback_clear("#feedback_<?php echo $this->moduleid; ?>")
                        }
                    });
                });

                $("#feedback_<?php echo $this->moduleid; ?>").submit(function () {

                    var data = gdfeedback_data("#feedback_<?php echo $this->moduleid; ?>");
                    $.post("<?php echo _LPATH_SITE; ?>/modules/mod_gdfeedback/ajax_email.php",
                        {
                            data: data,
                            keys: '<?php echo serialize($key_array); ?>',
                            email_to: '<?php echo $this->email_to; ?>',
                            email_c: '<?php echo $this->email_c; ?>',
                            email_cc: '<?php echo $this->email_cc; ?>',
                            name_form: encodeURIComponent('<?php echo $this->name_form; ?>'),
                            cptch: '<?php echo $this->captcha; ?>'
                        },
                        function (data) {
                            switch (data) {
                                case '1':
                                    $(".feedback_itog").html("<?php echo $_lang['MOD_GDFB_ERR_CODE']; ?>");
                                    gdfeedback_code_change(<?php echo $this->moduleid; ?>);
                                    break;
                                case '2':
                                    $(".feedback_itog").html("<?php echo $_lang['MOD_GDFB_ERR_EMAIL_TO']; ?>");
                                    gdfeedback_code_change(<?php echo $this->moduleid; ?>);
                                    break;
                                default:
                                    gdfeedback_code_change(<?php echo $this->moduleid; ?>);
                                    $(".feedback_box_ok").html("<?php echo $_lang['MOD_GDFB_MAIL_OK']; ?>");
                                    $(".feedback_box form").hide();
                            }
                        });
                    return false;
                });

            });
        </script>
        <?php
        return true;
    }

    /**
     * Получение настроек  модуля
     *
     * @param object $params   : Параметры модуля
     * @param int    $moduleid : Идентификатор модуля
     *
     * @modification 31.10.2013 Gold Dragon
     */
    private function getParams($params, $moduleid)
    {
        // подключаем языковой файл
        $_lang = LLang::getLang('mod.gdfeedback');

        // идентификатор модуля
        $this->moduleid = $moduleid;

        $this->error_view = intval($params->get('error_view', 1));

        $this->moduleclass_sfx = trim($params->get('moduleclass_sfx', ''));

        $this->name_form = trim(strip_tags($params->get('name_form', '')));

        $_email_to = trim($params->get('email_to', ''));
        // проверяем корректность email
        if (filter_var($_email_to, FILTER_VALIDATE_EMAIL)) {
            $this->email_to = $_email_to;
        } else {
            $this->error[] = $_lang['MOD_GDFB_ERR_NOT_EMAIL'];
        }

        $_email_c = trim($params->get('email_c', ''));
        $this->email_c = filter_var($_email_c, FILTER_VALIDATE_EMAIL) ? $_email_c : '';

        $_email_cc = trim($params->get('email_cc', ''));
        $this->email_cc = filter_var($_email_cc, FILTER_VALIDATE_EMAIL) ? $_email_cc : '';

        $this->captcha = intval($params->get('captcha', 1));

        $_template_dir = intval($params->get('template_dir', 0));
        $_email_format = intval($params->get('email_format', 0));

        $_form_template = trim(strip_tags($params->get('form_template', '')));

        // проверяем и загружаем шаблон письма
        if ($_template_dir and !empty($_form_template) and is_readable(_LPATH_TPL_FRONT . '/' . TEMPLATE . '/html/modules/mod_gdfeedback/' . $_form_template)) {
            $this->template = file_get_contents(_LPATH_TPL_FRONT . '/' . TEMPLATE . '/html/modules/mod_gdfeedback/' . $_form_template);
        } elseif (!empty($_form_template) and is_readable(_LPATH_TPL_MOD . '/mod_gdfeedback/' . $_form_template)) {
            $this->template = file_get_contents(_LPATH_TPL_MOD . '/mod_gdfeedback/' . $_form_template);
        } else {
            $this->error[] = $_lang['MOD_GDFB_ERR_NOT_TPL'];
        }

        // подключаем данные формы
        $_form = $params->get('form', '');

        // Нормализуем данные
        $this->form = parse_ini_string($this->strToIni($_form), true);

    }

    /**
     * Нормализация данных в формат INI
     *
     * @param $str : исходная строка
     *
     * @return string: нормализованная строка
     *
     * @modification 12.11.2013 Gold Dragon
     */
    private function strToIni($str)
    {
        $result = array();

        // разбиваем параметры
        $_array = preg_split("#<br[\s]?[\/]?>#ui", $str, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($_array as $value) {
            $value = trim($value);

            // отделяем разделы от параметров
            if (preg_match("#^\[.*\]$#su", $value)) {
                $result[] = $value;
            } elseif (strpos($value, '=')) {

                // проверяем параметры
                $_value = explode("=", $value, 2);

                // проверяем наличие значения
                if (trim($_value[1])) {
                    // экранируем значение
                    $result[] = $_value[0] . '="' . str_replace('"', '\\"', $_value[1]) . '"';
                }
            }
        }
        return implode("\n", $result);
    }

    /**
     * Вызывается при отсутствии метода обработки поля
     *
     * @param string $methodName : имя вызываемого метода
     * @param        $args       : передаваемые аргументы
     *
     * @modification 12.11.2013 Gold Dragon
     */
    public function __call($methodName, $args)
    {
        $_lang = LLang::getLang('mod.gdfeedback');
        $this->error[] = sprintf($_lang['MOD_GDFB_ERR_NOT_METHOD'], str_replace('field_', '', $methodName));
    }

    /**
     * Обработка поля TEXT
     *
     * @param string $key   : имя поля
     * @param array  $value : параметры поля
     *
     * @modification 12.11.2013 Gold Dragon
     */
    private function field_text($key, $value)
    {
        // Описание элемента
        $_desc = LCore::getParam($value, 'desc', '', 's');

        // Значение по умолчанию
        $_value = LCore::getParam($value, 'value', '', 's');

        // Максимальное количество символов в текстовом поле
        $_maxlength = LCore::getParam($value, 'maxlength', '', 's');

        // Подсказка в в текстовом поле
        $_placeholder = LCore::getParam($value, 'placeholder', '', 's');

        // Обязательность поля
        $_required = (LCore::getParam($value, 'required', 0, 'i')) ? 'required="required"' : '';

        // Количество символов, которые должно быть видны в поле ввода
        $_size = LCore::getParam($value, 'size', '', 's');

        // Тип элемента ввода
        $_type = LCore::getParam($value, 'type', '', 's');

        // замена названия
        $this->fields['[' . $key . '-d]'] = $_desc;

        // Замена поля
        $this->fields['[' . $key . '-k]']
            = '<input
            name="' . $key . '"
            id="' . $key . '"
            type="' . $_type . '"
            size="' . $_size . '"
            maxlength="' . $_maxlength . '"
            placeholder="' . $_placeholder . '"
            value="' . $_value . '"
            ' . $_required . ' />';
    }

    /**
     * Обработка поля textarea
     *
     * @param string $key   : имя поля
     * @param array  $value : параметры поля
     *
     * @modification 12.11.2013 Gold Dragon
     */
    private function field_textarea($key, $value)
    {
        // Описание элемента
        $_desc = LCore::getParam($value, 'desc', '', 's');

        // Значение по умолчанию
        $_value = LCore::getParam($value, 'value', '', 's');

        // Максимальное количество символов в текстовом поле
        $_maxlength = LCore::getParam($value, 'maxlength', 1000, 'i');

        // Подсказка в в текстовом поле
        $_placeholder = LCore::getParam($value, 'placeholder', '', 's');

        // Обязательность поля
        $_required = (LCore::getParam($value, 'required', 0, 'i')) ? 'required="required"' : '';

        // Видимая ширина текстовой области
        $_cols = LCore::getParam($value, 'cols', 50, 'i');

        // Видимое количество строк в текстовой области
        $_rows = LCore::getParam($value, 'rows', 5, 'i');

        // замена названия
        $this->fields['[' . $key . '-d]'] = $_desc;

        // Замена поля
        $this->fields['[' . $key . '-k]']
            = '<textarea
            placeholder="' . $_placeholder . '"
            name="' . $key . '"
            id="' . $key . '"
            cols="' . $_cols . '"
            rows="' . $_rows . '"
            maxlength="' . $_maxlength . '"
            ' . $_required . '>' . $_value . '</textarea>';
    }

    /**
     * Вывод ошибки модуля
     *
     * @modification 12.11.2013 Gold Dragon
     */
    private function getError()
    {
        if (sizeof($this->error)) {
            if ($this->error_view) {
                $_lang = LLang::getLang('mod.gdfeedback');
                echo '<div class="error' . $this->moduleclass_sfx . '">';
                echo '<h3>' . $_lang['MOD_GDFB_ERROR'] . '</h3>';
                echo implode('<br />', $this->error);
                echo '</div>';
            }
            return true;
        } else {
            return false;
        }
    }
}


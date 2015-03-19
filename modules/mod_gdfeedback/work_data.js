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

function gdfeedback_clear(form) {
    $(form).
        find("input[type='checkbox']:checked," +
            "input[type='email']," +
            "input[type='hidden']," +
            "input[type='password']," +
            "input[type='radio']:checked," +
            "input[type='text']," +
            "input[type='tel']," +
            "textarea," +
            "select").
        each(function () {
            if (this.tagName == 'SELECT' && $(this).attr('multiple') == true) {
                var select_name = this.name;
                $(this).find('option:selected').each(function () {
                    this.value = '';
                })
            } else {
                this.value = '';
            }
        });
}
function gdfeedback_data(form) {
    var a = [];
    $(form).
        find("input[type='checkbox']:checked," +
            "input[type='email']," +
            "input[type='hidden']," +
            "input[type='password']," +
            "input[type='radio']:checked," +
            "input[type='text']," +
            "input[type='tel']," +
            "textarea," +
            "select").
        each(function () {
            if (this.tagName == 'SELECT' && $(this).attr('multiple') == true) {
                var select_name = this.name;
                $(this).find('option:selected').each(function () {
                    a.push(select_name + '[]=' + this.value);
                })
            } else {
                var content = this.value;
                a.push(this.name + '=' + encodeURIComponent(content));
            }
        });

    return a.join('&');
}
function gdfeedback_code_change(id){
    $("#img_code_"+id).html("<img src='modules/mod_gdfeedback/ajax-loader.gif' />");
    $.get("modules/mod_gdfeedback/captcha.php", function(){
        $("#img_code_"+id).html("<img src='cache/code_image.png?"+gdfeedback_time()+"' />");
    });
    return false;
}
function gdfeedback_time(){
    return new Date().getTime();
}

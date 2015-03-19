<?php
/**
 * @package      GDFeedback
 * @version      1.0
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

session_start();

if(isset($_POST['data']) and isset($_SESSION['securityCode'])){
    // используемые поля в форме
    $keys_array = unserialize($_POST['keys']);

    // данные формы
    $data_array = explode('&', $_POST['data']);
    $_data_array = array();
    foreach($data_array as $value){
        $_value = explode('=', $value);
        $_data_array[$_value[0]] = urldecode($_value[1]);
    }

    // email Кому
    $email_to = $_POST['email_to'];

    // email Кому копия
    $email_c = $_POST['email_c'];

    // email Кому скрытая копия
    $email_cc = $_POST['email_cc'];

    // Название формы
    $name_form = urldecode($_POST['name_form']);

    // капча
    $cptch = $_POST['cptch'];

    // введённый проверочный код
    $code = (isset($_data_array['code'])) ? strtolower($_data_array['code']) : null;

    if($code != $_SESSION['securityCode'] and $cptch){
        echo 1; // Не верный проверочный код
    }elseif(!trim($email_to)){
        echo 2; // Не указан Email адресата
    }else{
        $to = $email_to;
        $subject = "=?utf-8?b?" . base64_encode($name_form) . "?=";
        $message = array();
        foreach($_data_array as $key=>$value){
            if(isset($keys_array[$key])){
                $message[] = $keys_array[$key] . ': ' . $value;
            }
        }
        $message = implode("<br>\r\n", $message);

        $headers = array();
        $headers[] = trim('MIME-Version: 1.0');
        $headers[] = trim('Content-type: text/html; charset=utf-8');

        $headers[] = trim('From: =?utf-8?b?' . base64_encode('Сайт ' . $_SERVER['SERVER_NAME']) . '?=');
        $headers[] = trim('Cc: ' . $email_c);
        $headers[] = trim('Bcc: ' . $email_cc);

        mail($to, $subject, $message, implode("\r\n", $headers));

    }
}

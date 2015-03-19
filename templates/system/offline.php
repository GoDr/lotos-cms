<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

include_once (_LPATH_ROOT . DS . 'language' . DS . LCore::getCfg('lang') . DS . 'system.php');

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?php echo LCore::getCfg('fromname') . ' ' .LCore::getCfg('config_sitename'); ?> - <?php echo _SITE_OFFLINE; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="<?php echo _LPATH_SITE; ?>/templates/css/offline.css" type="text/css"/>
        <link rel="shortcut icon" href="<?php echo _LPATH_SITE . '/favicon.ico'; ?>"/>
    </head>
    <body>
    <div style="text-align: center">
        <img width="500px" src="<?php echo _LPATH_SITE; ?>/images/lotos-cms.png" alt="<?php echo _SITE_OFFLINE ?>" align="middle"/>

        <p style="color: #f00"><?php echo LCore::getCfg('offline_message'); ?></p>

        <p style="font-size: 80%">
            <strong>Система управления веб-содержимым Lotos CMS</strong>
            <br>
            может использоваться на условиях лицензионного соглашения <a href="http://opensource.org/licenses/MIT">MIT License</a> и <a href="http://opensource.org/licenses/gpl-license">GNU General Public License</a>
            <br>
            Авторские права &copy; 2013-<?php echo date('Y') . ' <a href="http://lotos-cms.ru/">' . LVersion::getLongVersion(); ?></a>
        </p>

    </div>
    </body>
    </html>
<?php
exit(0);

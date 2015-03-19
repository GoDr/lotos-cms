<?php defined('_LINDEX') or die('STOP in file ' . __FILE__); ?>
<?php global $my, $mainframe; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php if ($my->id && $mainframe->allow_wysiwyg) {
        initEditor();
    } ?>
    <!--[if lt IE 9]><?php LHtml::addJS(_LPATH_TPL_FRONT_S . '/' . TEMPLATE . '/js/html5shiv.js'); ?><![endif]-->
    <?php LHtml::addCSS(_LPATH_TPL_FRONT_S . '/' . TEMPLATE . '/css/main.css'); ?>
    <?php mosShowHead(array('js' => 1, 'css' => 1, 'jquery' => 1)); ?>

</head>
<body>

<div id="tpl_body">

    <header>
        <div class="tpl_width">
            <div id="tpl_logo"></div>
            <div id="tpl_slogan"><?php mosLoadModules('header'); ?></div>
            <div id="tpl_top"><?php mosLoadModules('top'); ?></div>
            <nav id="tpl_menu"><?php mosLoadModules('menu_main'); ?></nav>
        </div>
    </header>

    <section id="tpl_center" class="tpl_width">
        <aside id="tpl_left">
            <?php mosLoadModules('user1'); ?>
            <?php mosLoadModules('left'); ?>
            <?php mosLoadModules('user2'); ?>
        </aside>
        <section id="tpl_right">
            <?php mosLoadModules('user3'); ?>
            <main><?php mosMainBody(); ?></main>
            <?php mosLoadModules('user4'); ?>
        </section>
    </section>

    <section id="tpl_down">
        <div class="tpl_width">
            <aside id="tpl_down_1"><?php mosLoadModules('user5'); ?></aside>
            <aside id="tpl_down_2"><?php mosLoadModules('user6'); ?></aside>
            <aside id="tpl_down_3"><?php mosLoadModules('user7'); ?></aside>
            <aside id="tpl_down_4"><?php mosLoadModules('user8'); ?></aside>
        </div>
    </section>

    <footer>
        <address class="tpl_width">
            <strong>Система управления веб-содержимым Lotos CMS</strong><br>
            может использоваться на условиях лицензионного соглашения <a href="http://opensource.org/licenses/MIT">MIT License</a> и <a href="http://opensource.org/licenses/gpl-license">GNU General Public License</a>
            <br>
            Авторские права &copy; 2013-<?php echo date('Y') . ' <a href="http://lotos-cms.ru/">' . LVersion::getLongVersion(); ?></a>
        </address>
    </footer>

</div>

<?php mosShowFooter(array('js' => 1)); ?>
<?php mosShowFooter(array('custom' => 1)); ?>

</body>
</html>







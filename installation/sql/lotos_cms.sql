CREATE TABLE IF NOT EXISTS `#__banners` (
    `id`                 INT(11)          NOT NULL AUTO_INCREMENT,
    `cid`                INT(11)          NOT NULL DEFAULT '0',
    `tid`                INT(11)          NOT NULL DEFAULT '0',
    `type`               VARCHAR(10)      NOT NULL DEFAULT 'banner',
    `name`               VARCHAR(50)      NOT NULL DEFAULT '',
    `imp_total`          INT(11)          NOT NULL DEFAULT '0',
    `imp_made`           INT(11)          NOT NULL DEFAULT '0',
    `clicks`             INT(11)          NOT NULL DEFAULT '0',
    `image_url`          VARCHAR(100) DEFAULT '',
    `click_url`          VARCHAR(200) DEFAULT '',
    `custom_banner_code` TEXT,
    `state`              TINYINT(1)       NOT NULL DEFAULT '0',
    `last_show`          DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `msec`               INT(11)          NOT NULL DEFAULT '0',
    `publish_up_date`    DATE             NOT NULL DEFAULT '0000-00-00',
    `publish_up_time`    TIME             NOT NULL DEFAULT '00:00:00',
    `publish_down_date`  DATE             NOT NULL DEFAULT '0000-00-00',
    `publish_down_time`  TIME             NOT NULL DEFAULT '00:00:00',
    `reccurtype`         TINYINT(1)       NOT NULL DEFAULT '0',
    `reccurweekdays`     VARCHAR(100)     NOT NULL DEFAULT '',
    `access`             INT(11)          NOT NULL DEFAULT '0',
    `target`             VARCHAR(15)      NOT NULL DEFAULT '',
    `border_value`       INT(11)          NOT NULL DEFAULT '0',
    `border_style`       VARCHAR(11)      NOT NULL DEFAULT '',
    `border_color`       VARCHAR(11)      NOT NULL DEFAULT '',
    `click_value`        VARCHAR(10)      NOT NULL DEFAULT '',
    `complete_clicks`    INT(11)          NOT NULL DEFAULT '0',
    `imp_value`          VARCHAR(10)      NOT NULL DEFAULT '',
    `dta_mod_clicks`     DATE DEFAULT NULL,
    `password`           VARCHAR(40)      NOT NULL DEFAULT '',
    `checked_out`        INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `checked_out_time`   DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `alt`                VARCHAR(200) DEFAULT '',
    `title`              VARCHAR(200) DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `ibx_select` (`state`, `last_show`, `msec`, `publish_up_date`, `publish_up_time`, `publish_down_date`, `publish_down_time`, `reccurtype`, `reccurweekdays`(2), `access`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__banners_categories` (
    `id`               INT(11)          NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(255)     NOT NULL DEFAULT '',
    `description`      TEXT,
    `published`        TINYINT(1)       NOT NULL DEFAULT '0',
    `checked_out`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `checked_out_time` DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY `published` (`published`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__banners_clients` (
    `cid`              INT(11)          NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(60)      NOT NULL DEFAULT '',
    `contact`          VARCHAR(60)      NOT NULL DEFAULT '',
    `email`            VARCHAR(60)      NOT NULL DEFAULT '',
    `extrainfo`        TEXT,
    `published`        TINYINT(1)       NOT NULL DEFAULT '0',
    `checked_out`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `checked_out_time` DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`cid`),
    KEY `published` (`published`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__boss_config` (
    `id`                        INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                      TEXT             NOT NULL,
    `slug`                      VARCHAR(200)     NOT NULL,
    `meta_title`                VARCHAR(60)      NOT NULL,
    `meta_desc`                 VARCHAR(200)     NOT NULL,
    `meta_keys`                 VARCHAR(200)     NOT NULL,
    `default_order_by`          VARCHAR(20)      NOT NULL,
    `contents_per_page_front`   INT(10) UNSIGNED NOT NULL DEFAULT '7',
    `contents_per_page`         INT(10) UNSIGNED NOT NULL DEFAULT '20',
    `root_allowed`              TINYINT(4)       NOT NULL DEFAULT '1',
    `send_email_on_new`         TINYINT(4)       NOT NULL DEFAULT '1',
    `send_email_on_update`      TINYINT(4)       NOT NULL DEFAULT '1',
    `auto_publish`              TINYINT(4)       NOT NULL DEFAULT '1',
    `fronttext`                 TEXT             NOT NULL,
    `email_display`             TINYINT(4)       NOT NULL DEFAULT '0',
    `display_fullname`          TINYINT(4)       NOT NULL DEFAULT '2',
    `rules_text`                TEXT             NOT NULL,
    `expiration`                TINYINT(1)       NOT NULL DEFAULT '0',
    `content_duration`          INT(4)           NOT NULL DEFAULT '30',
    `recall`                    TINYINT(1)       NOT NULL DEFAULT '1',
    `recall_time`               INT(4)           NOT NULL DEFAULT '7',
    `recall_text`               TEXT             NOT NULL,
    `empty_cat`                 TINYINT(1)       NOT NULL DEFAULT '1',
    `cat_max_width`             INT(4)           NOT NULL DEFAULT '150',
    `cat_max_height`            INT(4)           NOT NULL DEFAULT '150',
    `cat_max_width_t`           INT(4)           NOT NULL DEFAULT '30',
    `cat_max_height_t`          INT(4)           NOT NULL DEFAULT '30',
    `submission_type`           INT(4)           NOT NULL DEFAULT '30',
    `nb_contents_by_user`       INT(4)           NOT NULL DEFAULT '-1',
    `allow_attachement`         TINYINT(1)       NOT NULL DEFAULT '0',
    `allow_contact_by_pms`      TINYINT(1)       NOT NULL DEFAULT '0',
    `allow_comments`            TINYINT(1)       NOT NULL DEFAULT '0',
    `rating`                    VARCHAR(50)      NOT NULL,
    `secure_comment`            TINYINT(1)       NOT NULL DEFAULT '0',
    `comment_sys`               TINYINT(1)       NOT NULL,
    `allow_unregisered_comment` TINYINT(1)       NOT NULL,
    `allow_ratings`             TINYINT(1)       NOT NULL,
    `secure_new_content`        TINYINT(1)       NOT NULL DEFAULT '0',
    `use_content_plugin`        TINYINT(1)       NOT NULL DEFAULT '0',
    `show_rss`                  TINYINT(1)       NOT NULL DEFAULT '0',
    `filter`                    VARCHAR(50)      NOT NULL DEFAULT 'no',
    `template`                  VARCHAR(255)     NOT NULL DEFAULT 'default',
    `allow_rights`              VARCHAR(1)       NOT NULL DEFAULT '0',
    `rights`                    TEXT             NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__boss_plug_config` (
    `id`        INT(11)     NOT NULL AUTO_INCREMENT,
    `directory` INT(11)     NOT NULL,
    `plug_type` VARCHAR(11) NOT NULL,
    `plug_name` VARCHAR(30) NOT NULL,
    `title`     VARCHAR(30) NOT NULL,
    `value`     VARCHAR(30) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `directory` (`directory`, `plug_type`, `plug_name`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__components` (
    `id`              INT(11)          NOT NULL AUTO_INCREMENT,
    `name`            VARCHAR(50)      NOT NULL DEFAULT '',
    `link`            VARCHAR(255)     NOT NULL DEFAULT '',
    `menuid`          INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `parent`          INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `admin_menu_link` VARCHAR(255)     NOT NULL DEFAULT '',
    `admin_menu_alt`  VARCHAR(255)     NOT NULL DEFAULT '',
    `option`          VARCHAR(50)      NOT NULL DEFAULT '',
    `ordering`        INT(11)          NOT NULL DEFAULT '0',
    `admin_menu_img`  VARCHAR(255)     NOT NULL DEFAULT '',
    `iscore`          TINYINT(4)       NOT NULL DEFAULT '0',
    `params`          TEXT,
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`) VALUES
    (1, 'Баннеры', '', 0, 0, 'option=com_banners', 'Управление баннерами', 'com_banners', 0, 'js/ThemeOffice/component.png', 0, ''),
    (2, 'Баннеры', '', 0, 1, 'option=com_banners&task=banners', 'Активные баннеры', 'com_banners', 1, 'js/ThemeOffice/edit.png', 0, ''),
    (3, 'Клиенты', '', 0, 1, 'option=com_banners&task=clients', 'Управление клиентами', 'com_banners', 2, 'js/ThemeOffice/categories.png', 0, ''),
    (4, 'Категории', '', 0, 1, 'option=com_banners&task=categories', 'Управление категориями', 'com_banners', 2, 'js/ThemeOffice/categories.png', 0, ''),
    (5, 'Главная страница', 'option=com_frontpage', 0, 0, '', 'Управление объектами главной страницы', 'com_frontpage', 0, 'js/ThemeOffice/component.png', 1, ''),
    (6, 'Опросы', 'option=com_poll', 0, 0, 'option=com_poll', 'Управление опросами', 'com_poll', 0, 'js/ThemeOffice/component.png', 0, ''),
    (7, 'Авторизация', 'option=com_login', 0, 0, '', '', 'com_login', 0, '', 1, ''),
    (8, 'Поиск', 'option=com_search', 0, 0, '', '', 'com_search', 0, '', 1, ''),
    (9, 'RSS экспорт', '', 0, 0, 'option=com_syndicate&hidemainmenu=1', 'Управление настройками экспорта новостей', 'com_syndicate', 0, 'js/ThemeOffice/globe2_other.png', 0, 'check=0\ncache=1\ncache_time=3600\ncount=5\ntitle=Создано Joostina CMS!\ndescription=Экспорт с сайта Joostina!\nimage_file=aload.gif\nimage_alt=Создано Joostina CMS!\nlimit_text=1\ntext_length=20\nyandex=0\nrss091=0\nrss10=0\nrss20=1\natom03=0\nopml=0\norderby=rdate\nlive_bookmark=RSS2.0'),
    (10, 'Рассылка почты', '', 0, 0, 'option=com_massmail&hidemainmenu=1', 'Массовая рассылка почты', 'com_massmail', 0, 'js/ThemeOffice/mass_email.png', 0, ''),
    (11, 'Карта сайта', 'option=com_xmap', 0, 0, 'option=com_xmap', '', 'com_xmap', 0, 'js/ThemeOffice/map.png', 0, ''),
    (12, 'BOSS', 'option=com_boss', 0, 0, 'option=com_boss', 'BOSS', 'com_boss', 0, '../administrator/components/com_boss/images/16x16/component.png', 1, ''),
    (13, 'Категории', '', 0, 12, 'option=com_boss&act=categories', 'Категории', 'com_boss', 0, '../administrator/components/com_boss/images/16x16/categories.png', 0, NULL),
    (14, 'Контент', '', 0, 12, 'option=com_boss&act=contents', 'Контент', 'com_boss', 1, '../administrator/components/com_boss/images/16x16/contents.png', 0, NULL),
    (15, 'Управление', '', 0, 12, 'option=com_boss&act=manager', 'Управление', 'com_boss', 2, '../administrator/components/com_boss/images/16x16/manager.png', 0, NULL),
    (16, 'Конфигурация', '', 0, 12, 'option=com_boss&act=configuration', 'Конфигурация', 'com_boss', 3, '../administrator/components/com_boss/images/16x16/configuration.png', 0, NULL),
    (17, 'Поля', '', 0, 12, 'option=com_boss&act=fields', 'Поля', 'com_boss', 4, '../administrator/components/com_boss/images/16x16/fields.png', 0, NULL),
    (18, 'Шаблоны', '', 0, 12, 'option=com_boss&act=templates', 'Шаблоны', 'com_boss', 5, '../administrator/components/com_boss/images/16x16/templates.png', 0, NULL),
    (19, 'Поля-изображения', '', 0, 12, 'option=com_boss&act=fieldimage', 'Изображения', 'com_boss', 7, '../administrator/components/com_boss/images/16x16/fieldimage.png', 0, NULL),
    (20, 'Импорт / экспорт', '', 0, 12, 'option=com_boss&act=export_import', 'Импорт / экспорт', 'com_boss', 8, '../administrator/components/com_boss/images/16x16/export_import.png', 0, NULL),
    (21, 'Lotos SEF', 'option=com_jlotossef', 0, 0, 'option=com_jlotossef', 'JLotos SEF', 'com_jlotossef', 0, '../administrator/components/com_jlotossef/images/jlsef.png', 0, ''),
    (22, 'Настройки', '', 0, 21, 'option=com_jlotossef&task=configuration', 'Настройки', 'com_jlotossef', 0, '../administrator/components/com_jlotossef/images/configuration.png', 0, NULL),
    (23, 'Ссылки', '', 0, 21, 'option=com_jlotossef&task=references', 'Ссылки', 'com_jlotossef', 1, '../administrator/components/com_jlotossef/images/references.png', 0, NULL),
    (24, 'Дубликаты', '', 0, 21, 'option=com_jlotossef&task=duplicates', 'Дубликаты', 'com_jlotossef', 2, '../administrator/components/com_jlotossef/images/duplicates.png', 0, NULL),
    (25, 'Описание SEF-файлов', '', 0, 21, 'option=com_jlotossef&task=description', 'Описание SEF-файлов', 'com_jlotossef', 3, '../administrator/components/com_jlotossef/images/description.png', 0, NULL),
    (26, 'Файловый менеджер', 'option=com_filemanger', 0, 0, 'option=com_filemanager', 'Файловый менеджер', 'com_filemanager', 0, 'js/ThemeOffice/media.png', 0, NULL),
    (27, 'Настройки', '', 0, 11, 'option=com_xmap&task=configuration', 'Настройки', 'com_xmap', 1, '../administrator/components/com_xmap/images/configuration.png', 0, NULL),
    (28, 'Ссылки', '', 0, 11, 'option=com_xmap&task=configlink', 'Ссылки', 'com_xmap', 2, '../administrator/components/com_xmap/images/reference.png', 0, NULL),
    (29, 'Создать Sitemap.xml', '', 0, 11, 'option=com_xmap&task=sitemap', 'Создать Sitemap.xml', 'com_xmap', 3, '../administrator/components/com_xmap/images/sitemap.png', 0, NULL);


CREATE TABLE IF NOT EXISTS `#__config` (
    `id`       INT(11)      NOT NULL AUTO_INCREMENT,
    `group`    VARCHAR(255) NOT NULL,
    `subgroup` VARCHAR(255) NOT NULL,
    `name`     VARCHAR(50)  NOT NULL,
    `value`    TEXT,
    PRIMARY KEY (`id`),
    KEY `name` (`name`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__content_rating` (
    `content_id`   INT(11)          NOT NULL DEFAULT '0',
    `rating_sum`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `rating_count` INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `lastip`       VARCHAR(50)      NOT NULL DEFAULT '',
    PRIMARY KEY (`content_id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__content_tags` (
    `id`       INT(11)      NOT NULL AUTO_INCREMENT,
    `obj_id`   INT(11)      NOT NULL,
    `obj_type` VARCHAR(255) NOT NULL,
    `tag`      VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `obj_id` (`obj_id`, `tag`),
    KEY `obj_type` (`obj_type`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__core_acl_aro` (
    `aro_id`        INT(11)      NOT NULL AUTO_INCREMENT,
    `section_value` VARCHAR(240) NOT NULL DEFAULT '0',
    `value`         INT(11)      NOT NULL,
    `order_value`   INT(11)      NOT NULL DEFAULT '0',
    `name`          VARCHAR(255) NOT NULL DEFAULT '',
    `hidden`        INT(11)      NOT NULL DEFAULT '0',
    PRIMARY KEY (`aro_id`),
    UNIQUE KEY `value` (`value`),
    UNIQUE KEY `#__gacl_section_value_value_aro` (`section_value`(100), `value`),
    KEY `#__gacl_hidden_aro` (`hidden`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__core_acl_aro` (`aro_id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES
    (10, 'users', 62, 0, 'Administrator', 0);

CREATE TABLE IF NOT EXISTS `#__core_acl_aro_groups` (
    `group_id`  INT(11)      NOT NULL AUTO_INCREMENT,
    `parent_id` INT(11)      NOT NULL DEFAULT '0',
    `name`      VARCHAR(255) NOT NULL DEFAULT '',
    `lft`       INT(11)      NOT NULL DEFAULT '0',
    `rgt`       INT(11)      NOT NULL DEFAULT '0',
    PRIMARY KEY (`group_id`),
    KEY `#__gacl_parent_id_aro_groups` (`parent_id`),
    KEY `#__gacl_lft_rgt_aro_groups` (`lft`, `rgt`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__core_acl_aro_groups` (`group_id`, `parent_id`, `name`, `lft`, `rgt`) VALUES
    (17, 0, 'ROOT', 1, 22),
    (28, 17, 'USERS', 2, 21),
    (29, 28, 'Public Frontend', 3, 12),
    (18, 29, 'Registered', 4, 11),
    (19, 18, 'Author', 5, 10),
    (20, 19, 'Editor', 6, 9),
    (21, 20, 'Publisher', 7, 8),
    (30, 28, 'Public Backend', 13, 20),
    (23, 30, 'Manager', 14, 19),
    (24, 23, 'Administrator', 15, 18),
    (25, 24, 'Super Administrator', 16, 17);

CREATE TABLE IF NOT EXISTS `#__core_acl_aro_sections` (
    `section_id`  INT(11)      NOT NULL AUTO_INCREMENT,
    `value`       VARCHAR(230) NOT NULL DEFAULT '',
    `order_value` INT(11)      NOT NULL DEFAULT '0',
    `name`        VARCHAR(230) NOT NULL DEFAULT '',
    `hidden`      INT(11)      NOT NULL DEFAULT '0',
    PRIMARY KEY (`section_id`),
    UNIQUE KEY `value_aro_sections` (`value`),
    KEY `hidden_aro_sections` (`hidden`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__core_acl_aro_sections` (`section_id`, `value`, `order_value`, `name`, `hidden`) VALUES
    (10, 'users', 1, 'Users', 0);

CREATE TABLE IF NOT EXISTS `#__core_acl_groups_aro_map` (
    `group_id`      INT(11)      NOT NULL DEFAULT '0',
    `section_value` VARCHAR(240) NOT NULL DEFAULT '',
    `aro_id`        INT(11)      NOT NULL DEFAULT '0',
    UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`, `section_value`, `aro_id`),
    KEY `aro_id` (`aro_id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`) VALUES
    (18, '', 11),
    (25, '', 10);

CREATE TABLE IF NOT EXISTS `#__core_log_items` (
    `time_stamp` DATE             NOT NULL DEFAULT '0000-00-00',
    `item_table` VARCHAR(50)      NOT NULL DEFAULT '',
    `item_id`    INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `hits`       INT(11) UNSIGNED NOT NULL DEFAULT '0'
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__core_log_searches` (
    `search_term` VARCHAR(128)     NOT NULL DEFAULT '',
    `hits`        INT(11) UNSIGNED NOT NULL DEFAULT '0',
    KEY `hits` (`hits`),
    KEY `search_term` (`search_term`(16))
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__groups` (
    `id`   TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `name` VARCHAR(50)         NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__groups` (`id`, `name`) VALUES
    (0, 'Общий'),
    (1, 'Участники'),
    (2, 'Специальный');

CREATE TABLE IF NOT EXISTS `#__jp_def` (
    `def_id`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `directory` VARCHAR(255)     NOT NULL,
    PRIMARY KEY (`def_id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__jp_packvars` (
    `id`     INT(11)      NOT NULL AUTO_INCREMENT,
    `key`    VARCHAR(255) NOT NULL,
    `value`  VARCHAR(255) DEFAULT NULL,
    `value2` LONGTEXT,
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__menu` (
    `id`               INT(11)             NOT NULL AUTO_INCREMENT,
    `menutype`         INT(11) DEFAULT NULL,
    `name`             VARCHAR(100) DEFAULT NULL,
    `link`             TEXT,
    `type`             VARCHAR(50)         NOT NULL DEFAULT '',
    `published`        TINYINT(1)          NOT NULL DEFAULT '0',
    `parent`           INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `componentid`      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `sublevel`         INT(11) DEFAULT '0',
    `ordering`         INT(11) DEFAULT '0',
    `checked_out`      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `checked_out_time` DATETIME            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `pollid`           INT(11)             NOT NULL DEFAULT '0',
    `browserNav`       TINYINT(4) DEFAULT '0',
    `access`           TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `utaccess`         TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `params`           TEXT,
    PRIMARY KEY (`id`),
    KEY `componentid` (`componentid`, `menutype`, `published`, `access`),
    KEY `menutype` (`menutype`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__menu_type` (
    `id`    INT(11)      NOT NULL AUTO_INCREMENT,
    `type`  VARCHAR(50)  NOT NULL DEFAULT '0',
    `title` VARCHAR(250) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__modules` (
    `id`               INT(11)             NOT NULL AUTO_INCREMENT,
    `title`            TEXT,
    `content`          TEXT,
    `ordering`         INT(11)             NOT NULL DEFAULT '0',
    `position`         VARCHAR(10) DEFAULT NULL,
    `checked_out`      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `checked_out_time` DATETIME            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `published`        TINYINT(1)          NOT NULL DEFAULT '0',
    `module`           VARCHAR(50) DEFAULT NULL,
    `numnews`          INT(11)             NOT NULL DEFAULT '0',
    `access`           TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `showtitle`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `params`           TEXT,
    `iscore`           TINYINT(4)          NOT NULL DEFAULT '0',
    `client_id`        TINYINT(4)          NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `published` (`published`, `access`),
    KEY `newsfeeds` (`module`, `published`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES
    (1, 'Объекты компонента BOSS', '', 0, 'advert1', 0, '0000-00-00 00:00:00', 1, 'mod_boss_admin_contents', 0, 99, 1, 'moduleclass_sfx=\ncache=0\nlimit=15\npubl=0\ndisplaycategory=1\ncontent_title=Последние добавленные объекты\ncontent_title_link=Все объекты\nsort=5\ndate_field=date_created\ndisplay_author=1\ndirectory=1\ncat_ids=', 1, 1),
    (2, 'Полное меню', '', 0, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_fullmenu', 0, 99, 1, '', 1, 1),
    (3, 'Последние зарегистрированные пользователи', '', 0, 'advert2', 0, '0000-00-00 00:00:00', 1, 'mod_latest_users', 0, 99, 1, 'num=10\nshow_logged=1\nshow_total=1\nshow_today=1\nshow_week=1\nshow_month=1', 1, 1),
    (4, 'На сайте', '', 0, 'advert2', 0, '0000-00-00 00:00:00', 1, 'mod_logged', 0, 99, 1, '', 1, 1),
    (5, 'Системные сообщения', '', 0, 'inset', 0, '0000-00-00 00:00:00', 1, 'mod_mosmsg', 0, 99, 1, '', 1, 1),
    (6, 'Активные пользователи', '', 0, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_online', 0, 99, 1, '', 1, 1),
    (7, 'Кнопки быстрого доступа', '', 0, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicons', 0, 99, 1, '', 1, 1),
    (8, 'Меню', '', 0, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_stats', 0, 99, 1, '', 0, 1),
    (9, 'Панель инструментов', '', 0, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 99, 1, '', 1, 1),
    (10, 'mod_banners', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_banners', 0, 0, 0, '', 1, 0),
    (11, 'mod_boss_content', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_boss_content', 0, 0, 1, '', 1, 0),
    (12, 'mod_calendar', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_calendar', 0, 0, 1, '', 1, 0),
    (13, 'mod_gdfeedback', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_gdfeedback', 0, 0, 0, '', 1, 0),
    (14, 'mod_gdnlotos', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_gdnlotos', 0, 0, 0, '', 1, 0),
    (15, 'mod_gdslider', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_gdslider', 0, 0, 0, '', 1, 0),
    (16, 'mod_insert_php', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_insert_php', 0, 0, 0, '', 1, 0),
    (17, 'mod_menu_easy', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_menu_easy', 0, 0, 0, '', 1, 0),
    (18, 'mod_mljoostinamenu', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_mljoostinamenu', 0, 0, 0, '', 1, 0),
    (19, 'mod_ml_login', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_ml_login', 0, 0, 0, '', 1, 0),
    (20, 'mod_poll', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_poll', 0, 0, 0, '', 1, 0),
    (21, 'mod_quote', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_quote', 0, 0, 0, '', 1, 0),
    (22, 'mod_random_image', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_random_image', 0, 0, 0, '', 1, 0),
    (23, 'mod_related_items', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_related_items', 0, 0, 0, '', 1, 0),
    (24, 'mod_rssfeed', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_rssfeed', 0, 0, 0, '', 1, 0),
    (25, 'mod_search', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_search', 0, 0, 0, '', 1, 0),
    (26, 'mod_templatechooser', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_templatechooser', 0, 0, 0, '', 1, 0),
    (27, 'mod_whosonline', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_whosonline', 0, 0, 0, '', 1, 0),
    (28, 'mod_wrapper', '', 0, 'zero', 0, '0000-00-00 00:00:00', 0, 'mod_wrapper', 0, 0, 0, '', 1, 0);

CREATE TABLE IF NOT EXISTS `#__modules_com` (
    `id`        INT(11)     NOT NULL AUTO_INCREMENT,
    `moduleid`  INT(11)     NOT NULL DEFAULT '0',
    `option`    VARCHAR(20) NOT NULL DEFAULT '',
    `directory` INT(4)      NOT NULL DEFAULT '0',
    `category`  INT(4)      NOT NULL DEFAULT '0',
    `task`      VARCHAR(20) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE KEY `index2` (`moduleid`, `directory`, `category`, `option`, `task`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__plugins` (
    `id`               INT(11)             NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(100)        NOT NULL DEFAULT '',
    `element`          VARCHAR(100)        NOT NULL DEFAULT '',
    `folder`           VARCHAR(100)        NOT NULL DEFAULT '',
    `access`           TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `ordering`         INT(11)             NOT NULL DEFAULT '0',
    `published`        TINYINT(3)          NOT NULL DEFAULT '0',
    `iscore`           TINYINT(3)          NOT NULL DEFAULT '0',
    `client_id`        TINYINT(3)          NOT NULL DEFAULT '0',
    `checked_out`      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `checked_out_time` DATETIME            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params`           TEXT,
    PRIMARY KEY (`id`),
    KEY `idx_folder` (`published`, `client_id`, `access`, `folder`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
    (1, 'Поиск в контенте com_boss', 'boss.searchbot', 'search', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'directory=1\ncontent_field=content_editor\nsearch_limit=50\ngroup_results=1'),
    (2, 'CKEditor L', 'ckeditor', 'editors', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', NULL),
    (3, 'JLImage', 'jlimage', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'directory=5\ncatid=\nsize=200\nquality=75\nstyle=0\nstyle_default=left\ncorrect=1\nignor_small=1\noverlay_opacity=0.5\noverlay_color=#000\ntransition_in=elastic\ntransition_out=fade\nspeed_in=600\nspeed_out=600'),
    (4, 'Кнопка запреты использования JLImage', 'jlimage.btn', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', NULL),
    (5, 'Позиции загрузки модуля', 'loadposition', 'content', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
    (6, 'Простой редактор HTML', 'none', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', '');

CREATE TABLE IF NOT EXISTS `#__polls` (
    `id`               INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`            VARCHAR(100)     NOT NULL DEFAULT '',
    `voters`           INT(9)           NOT NULL DEFAULT '0',
    `checked_out`      INT(11)          NOT NULL DEFAULT '0',
    `checked_out_time` DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `published`        TINYINT(1)       NOT NULL DEFAULT '0',
    `access`           INT(11)          NOT NULL DEFAULT '0',
    `lag`              INT(11)          NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;


CREATE TABLE IF NOT EXISTS `#__poll_data` (
    `id`     INT(11) NOT NULL AUTO_INCREMENT,
    `pollid` INT(4)  NOT NULL DEFAULT '0',
    `text`   TEXT,
    `hits`   INT(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `pollid` (`pollid`, `text`(1))
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;


CREATE TABLE IF NOT EXISTS `#__poll_date` (
    `id`      BIGINT(20) NOT NULL AUTO_INCREMENT,
    `date`    DATETIME   NOT NULL DEFAULT '0000-00-00 00:00:00',
    `vote_id` INT(11)    NOT NULL DEFAULT '0',
    `poll_id` INT(11)    NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `poll_id` (`poll_id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__poll_menu` (
    `id`        INT(11)     NOT NULL AUTO_INCREMENT,
    `pollid`    INT(11)     NOT NULL DEFAULT '0',
    `option`    VARCHAR(20) NOT NULL DEFAULT '',
    `directory` INT(4)      NOT NULL DEFAULT '0',
    `category`  INT(4)      NOT NULL DEFAULT '0',
    `task`      VARCHAR(20) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE KEY `index2` (`pollid`, `directory`, `category`, `option`, `task`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__quickicons` (
    `id`         INT(11)             NOT NULL AUTO_INCREMENT,
    `text`       VARCHAR(64)         NOT NULL DEFAULT '',
    `target`     VARCHAR(255)        NOT NULL DEFAULT '',
    `icon`       VARCHAR(100)        NOT NULL DEFAULT '',
    `ordering`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `new_window` TINYINT(1)          NOT NULL DEFAULT '0',
    `published`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `title`      VARCHAR(64)         NOT NULL DEFAULT '',
    `display`    TINYINT(1)          NOT NULL DEFAULT '0',
    `access`     INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `gid`        INT(3) DEFAULT '25',
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__quickicons` (`id`, `text`, `target`, `icon`, `ordering`, `new_window`, `published`, `title`, `display`, `access`, `gid`) VALUES
    (1, 'Управление контентом', 'index2.php?option=com_boss', '/templates/admin/joostfree/images/cpanel_ico/all_content.png', 0, 0, 1, 'Управление объектами содержимого', 0, 0, 0),
    (2, 'Главная страница', 'index2.php?option=com_frontpage', '/templates/admin/joostfree/images/cpanel_ico/frontpage.png', 0, 0, 1, 'Управление объектами главной страницы', 0, 0, 0),
    (3, 'Корзина', 'index2.php?option=com_trash', '/templates/admin/joostfree/images/cpanel_ico/trash.png', 0, 0, 1, 'Управление корзиной удаленных объектов', 0, 0, 0),
    (4, 'Редактор меню', 'index2.php?option=com_menumanager', '/templates/admin/joostfree/images/cpanel_ico/menu.png', 0, 0, 1, 'Управление объектами меню', 0, 0, 24),
    (5, 'Пользователи', 'index2.php?option=com_users', '/templates/admin/joostfree/images/cpanel_ico/user.png', 0, 0, 1, 'Управление пользователями', 0, 0, 24),
    (6, 'Глобальная конфигурация', 'index2.php?option=com_config&hidemainmenu=1', '/templates/admin/joostfree/images/cpanel_ico/config.png', 0, 0, 1, 'Глобальная конфигурация сайта', 0, 0, 25),
    (7, 'Очистить весь кеш', 'index2.php?option=com_admin&task=clean_all_cache', '/templates/admin/joostfree/images/cpanel_ico/clear.png', 0, 0, 1, 'Очистить весь кеш сайта', 0, 0, 24),
    (8, 'Управление SEF', 'index2.php?option=com_jlotossef', '/templates/admin/joostfree/images/cpanel_ico/config.png', 0, 0, 1, '', 0, 0, 25);

CREATE TABLE IF NOT EXISTS `#__sef_config` (
    `id`    INT(10)     NOT NULL AUTO_INCREMENT,
    `name`  VARCHAR(50) NOT NULL DEFAULT '',
    `value` TEXT        NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `Index 2` (`name`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__sef_duplicate` (
    `id`  INT(11) NOT NULL AUTO_INCREMENT,
    `url` VARCHAR(255) DEFAULT NULL,
    `sef` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `url` (`url`),
    KEY `sef` (`sef`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__sef_link` (
    `id`  INT(11) NOT NULL AUTO_INCREMENT,
    `url` VARCHAR(255) DEFAULT NULL,
    `sef` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `url` (`url`),
    KEY `sef` (`sef`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__session` (
    `username`   VARCHAR(50) DEFAULT '',
    `time`       VARCHAR(14) DEFAULT '',
    `session_id` VARCHAR(200)        NOT NULL DEFAULT '0',
    `guest`      TINYINT(4) DEFAULT '1',
    `userid`     INT(11) DEFAULT '0',
    `usertype`   VARCHAR(50) DEFAULT '',
    `gid`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`session_id`(64)),
    KEY `whosonline` (`guest`, `usertype`),
    KEY `userid` (`userid`),
    KEY `time` (`time`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__stats_agents` (
    `agent` VARCHAR(255)        NOT NULL DEFAULT '',
    `type`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `hits`  INT(11) UNSIGNED    NOT NULL DEFAULT '1',
    KEY `type_agent` (`type`, `agent`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__templates_menu` (
    `template`  VARCHAR(50) NOT NULL DEFAULT '',
    `menuid`    INT(11)     NOT NULL DEFAULT '0',
    `client_id` TINYINT(4)  NOT NULL DEFAULT '0',
    PRIMARY KEY (`template`, `menuid`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__templates_menu` (`template`, `menuid`, `client_id`) VALUES
    ('default', 0, 0),
    ('joostfree', 0, 1);

CREATE TABLE IF NOT EXISTS `#__template_positions` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `position`    VARCHAR(10)  NOT NULL DEFAULT '',
    `description` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__template_positions` (`id`, `position`, `description`) VALUES
    (1, 'header', 'header'),
    (2, 'footer', 'footer'),
    (3, 'top', 'top'),
    (4, 'bottom', 'bottom'),
    (5, 'menu_main', 'menu_main'),
    (6, 'menu_top', 'menu_top'),
    (7, 'menu_add', 'menu_add'),
    (8, 'left', 'left'),
    (9, 'right', 'right'),
    (10, 'pathway', 'pathway'),
    (11, 'cpanel', 'cpanel (admin)'),
    (12, 'banner1', 'banner1'),
    (13, 'banner2', 'banner2'),
    (14, 'banner3', 'banner3'),
    (15, 'user1', 'user1'),
    (16, 'user2', 'user2'),
    (17, 'user3', 'user3'),
    (18, 'user4', 'user4'),
    (19, 'user5', 'user5'),
    (20, 'user6', 'user6'),
    (21, 'user7', 'user7'),
    (22, 'user8', 'user8'),
    (23, 'user9', 'user9'),
    (24, 'zero', 'zero'),
    (25, 'advert1', 'advert1 (admin)'),
    (26, 'advert2', 'advert2(admin)'),
    (27, 'icon', 'icon (admin)'),
    (28, 'inset', 'inset (admin)'),
    (29, 'toolbar', 'toolbar (admin)');

CREATE TABLE IF NOT EXISTS `#__users` (
    `id`             INT(11)             NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(50)         NOT NULL DEFAULT '',
    `username`       VARCHAR(100)        DEFAULT NULL,
    `email`          VARCHAR(100)        NOT NULL DEFAULT '',
    `password`       VARCHAR(100)        NOT NULL DEFAULT '',
    `usertype`       VARCHAR(25)         NOT NULL DEFAULT '',
    `block`          TINYINT(4)          NOT NULL DEFAULT '0',
    `sendEmail`      TINYINT(4) DEFAULT '0',
    `gid`            TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `registerDate`   DATETIME            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `lastvisitDate`  DATETIME            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `activation`     VARCHAR(100)        NOT NULL DEFAULT '',
    `params`         TEXT,
    `bad_auth_count` INT(2) DEFAULT '0',
    `avatar`         VARCHAR(255)        NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `usertype` (`usertype`),
    KEY `idx_name` (`name`),
    KEY `idxemail` (`email`),
    KEY `block_id` (`block`, `id`),
    KEY `username` (`username`)
)
    COLLATE='utf8_general_ci'
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__users_extra` (
    `user_id`   INT(11)      NOT NULL,
    `gender`    VARCHAR(10)  NOT NULL,
    `about`     TINYTEXT,
    `location`  VARCHAR(255) NOT NULL,
    `url`       VARCHAR(255) NOT NULL,
    `icq`       VARCHAR(255) NOT NULL,
    `skype`     VARCHAR(255) NOT NULL,
    `jabber`    VARCHAR(255) NOT NULL,
    `msn`       VARCHAR(255) NOT NULL,
    `yahoo`     VARCHAR(255) NOT NULL,
    `phone`     VARCHAR(255) NOT NULL,
    `fax`       VARCHAR(255) NOT NULL,
    `mobil`     VARCHAR(255) NOT NULL,
    `birthdate` DATETIME DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`user_id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__usertypes` (
    `id`   TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `name` VARCHAR(50)         NOT NULL DEFAULT '',
    `mask` VARCHAR(11)         NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__usertypes` (`id`, `name`, `mask`) VALUES
    (0, 'superadministrator', ''),
    (1, 'administrator', ''),
    (2, 'editor', ''),
    (3, 'user', ''),
    (4, 'author', ''),
    (5, 'publisher', ''),
    (6, 'manager', '');

CREATE TABLE IF NOT EXISTS `#__xmap` (
    `name`  VARCHAR(30) NOT NULL DEFAULT '',
    `value` VARCHAR(100)         DEFAULT NULL,
    PRIMARY KEY (`name`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

INSERT INTO `#__xmap` (`name`, `value`) VALUES
    ('templatename', 'default.tpl'),
    ('template', '0'),
    ('cachetime', '0'),
    ('cache', '0'),
    ('sef', '1');

CREATE TABLE IF NOT EXISTS `#__xmap_ext` (
    `id`     INT(11)     NOT NULL AUTO_INCREMENT,
    `plugin` VARCHAR(30) NOT NULL,
    `params` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `plugin` (`plugin`)
)
    ENGINE =MyISAM
    DEFAULT CHARSET =utf8;

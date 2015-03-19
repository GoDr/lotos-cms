<?php

define('_DIR_ROOT', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
define('_DIR_ROOT_COM', dirname(__FILE__));

require_once(_DIR_ROOT . '/configuration.php');

include_once _DIR_ROOT_COM . '/elFinderConnector.class.php';
include_once _DIR_ROOT_COM . '/elFinder.class.php';
include_once _DIR_ROOT_COM . '/elFinderVolumeDriver.class.php';
include_once _DIR_ROOT_COM . '/elFinderVolumeLocalFileSystem.class.php';

$path = (empty($mosConfig_dir_edit)) ? $mosConfig_absolute_path : $mosConfig_absolute_path . '/' . $mosConfig_dir_edit;
$url = (empty($mosConfig_dir_edit)) ? $mosConfig_live_site : $mosConfig_live_site . '/' . $mosConfig_dir_edit;

$opts = array(
    'locale' => 'ru_RU.UTF-8',
    'roots'  => array(
        array(
            'driver'     => 'LocalFileSystem',
            'path'       => $path . '/',
            'URL'        => $url . '/',
            'tmbPath'    => $mosConfig_cachepath . '/.tmp/',
            'tmbURL'     => $mosConfig_live_site . '/cache/.tmp/',
            'dateFormat' => 'd.m.Y H:i',
            'quarantine' => $mosConfig_cachepath . '/.quarantine/'
        )
    )
);

$connector = new elFinderConnector(new elFinder($opts));
$connector->run();
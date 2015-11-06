<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 31.05.15
 * Time: 4:30
 * To change this template use File | Settings | File Templates.
 */
session_start();
include_once('tools.php');
include_once('cStoneSet.php');
$year = file_get_contents('version.txt');
$MAIN_DIR = '';
$ATHLAS_CONTENT_DIR= $MAIN_DIR.'athlas_content_'.$year.'/';
$CONTENT_DIR = $MAIN_DIR.'content_'.$year.'/';
$TEMPLATE_DIR= $MAIN_DIR.'templates/';


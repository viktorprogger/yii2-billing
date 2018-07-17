<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

$vendor = dirname(__DIR__) . '/vendor';
define('VENDOR_DIR', $vendor);
require_once $vendor . '/autoload.php';
require $vendor . '/yiisoft/yii2/Yii.php';

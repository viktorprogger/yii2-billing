<?php

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=billing_test',
    'username' => 'billing_test',
    'password' => 'billing_test',
    'charset' => 'utf8',
];

if (file_exists(__DIR__ . '/db.local.php')) {
    $db = array_merge($db, require(__DIR__ . '/db.local.php'));
}

return $db;

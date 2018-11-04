<?php

use yii\console\controllers\MigrateController;

return [
    'id' => 'yii2-test-console',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@miolae/billing' => dirname(__DIR__, 3),
        '@tests' => dirname(__DIR__, 2),
    ],
    'components' => [
        'log'   => null,
        'cache' => null,
        'db'    => require __DIR__ . '/db.php',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => ['@miolae/billing/migrations'],
        ],
    ],
    'modules' => [
        'billing' => [
            'class' => \miolae\billing\Module::class,
        ],
    ],
];

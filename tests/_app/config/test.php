<?php

use yii\console\controllers\MigrateController;

return [
    'id' => 'yii2-billing-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@miolae/billing' => dirname(__DIR__, 3),
        '@tests' => dirname(__DIR__, 2),
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower-asset',
    ],
    'modules' => [
        'billing' => \miolae\billing\Module::class,
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
        'log' => null,
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => ['@app/migrations'],
        ],
    ],
    'params' => [],
];

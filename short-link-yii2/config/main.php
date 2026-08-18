<?php
/**
 * Application configuration for Yii2 Short Link App
 */
return [
    'id' => 'short-link-yii2',
    'name' => 'Short Link Service',
    'language' => 'ru',
    'timeZone' => 'UTC',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'your-secret-key-here',
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'sendMailNow' => false,
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:@app/data/short-link.db',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'register' => 'auth/register',
                'admin' => 'link/index',
                'link/create' => 'link/create',
                '<code:[A-Za-z0-9]{6}>' => 'link/redirect',
            ],
        ],
    ],
    'params' => [
        'codeLength' => 6,
    ],
];

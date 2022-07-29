<?php

use app\models\User;
use yii\caching\FileCache;
use yii\i18n\Formatter;
use yii\i18n\PhpMessageSource;
use yii\log\FileTarget;
use yii\swiftmailer\Mailer;
use yii\web\UrlNormalizer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'name' => 'yii2-image-storage',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Moscow',

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'components' => [

        'formatter' => [
            'class' => Formatter::class,
            'locale' => 'ru-RU',
            'thousandSeparator' => ' ',
            'decimalSeparator' => ',',
            'nullDisplay' => '-',
            'currencyCode' => 'RUB',
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy', //'d.MM.Y',
            'timeFormat' => 'H:mm:ss',
            //'datetimeFormat' => 'php:Y-m-d H:i:s T O',
            //'datetimeFormat' => 'd.MM.Y HH:mm',
            //'datetimeFormat' => 'php:Y-m-d H:i:s',
            'datetimeFormat' => 'php:d.m.y H:i',
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/messages',
                ],

                'app' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/messages',
                ],
            ],
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'siGjzYSJhZFV3q66a1VUSoozs7qEZcjQ',
        ],

        'cache' => [
            'class' => FileCache::class,
        ],

        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,

            'normalizer' => [
                'class' => UrlNormalizer::class,
            ],

            'rules' => [
            ]

        ],

        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

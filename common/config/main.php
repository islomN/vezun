<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@telegram_cache_files' => '@common/telegram-cache-files',
        '@server' => 'https://84cdb9ea.ngrok.io/telegram'

    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'telegram' => [
            'class' => 'common\telegram\Telegram',
        ]
    ],
];

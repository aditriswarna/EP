<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 1200,
        ],
        'awssdk' => [
            'class' => 'fedemotta\awssdk\AwsSdk',
            'credentials' => [ //you can use a different method to grant access
                'key' => 'AKIAJIHC4NXCSI4XRXNA',
                'secret' => '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',
            ],
            'region' => 'ap-south-1', //i.e.: 'us-east-1'
            'version' => 'latest', //i.e.: 'latest'
        ],
    ],
    
];

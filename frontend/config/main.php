<?php
//use \yii\web\Request;
//$baseUrl = str_replace('/frontend/web', '/frontend', (new Request)->getBaseUrl());
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            // 'baseUrl' => $baseUrl,
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                'login'=>'site/login',
                'signup'=>'site/signup',
                'create-admin-user'=>'admin/create',
                'create-project'=>'projects/create',
                'edit-project'=>'projects/update',
                'participate'=>'project-participation/create',
                'project-participation'=>'project-participation/index',
                'project-co-owners'=>'project-co-owners/index',
                'create-co-owner'=>'project-co-owners/create',
//                'projects/<title:\d+>/<cat:\d+>/<type:\d+>/<status:\d+>/<from:\d+>/<to:\d+>'=>'projects/index',                
                'private-project-requests'=>'projects/approve',
                'inbox'=>'communique/inbox-mails',
                'sent'=>'communique/sent-mails',                
                'profile'=>'site/user-profile',
                'dashboard'=>'site/dashboard',
                'search-projects' => 'site/dynamic-new',
                'compose-mail'=>'communique/new-message',
                'how-it-works'=>'site/how-it-works',
                'privacy-policy'=>'site/privacy-policy',
                'terms-of-use'=>'site/terms-of-use',
                'contact-us'=>'site/contact-us'
//                'edit-project/<id:\d+>' => 'projects/update'
            ],
        ],  
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],*/
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        //'//code.jquery.com/jquery-1.10.2.min.js',  // use custom jquery
                    ]
                ],
            ],
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

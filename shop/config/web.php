<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id'           => 'basic',
    'basePath'     => dirname(__DIR__),
    'bootstrap'    => ['log'],
    'defaultRoute' => 'index',
    //设置默认首页界面
    'components'   => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'q6ga0ArPuP1iWsey2H6aoeWsP7G98FnL',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport'        => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => 'smtp.163.com',
                'username'   => 'computerobot@163.com',
                'password'   => 'shop123',
                'port'       => '465',
                'encryption' => 'ssl',
            ],
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            // 'useFileTransport' => false,
            // 'transport'        => [
            //     'class'      => 'Swift_SmtpTransport',
            //     'host'       => 'smtp.163.com',
            //     'username'   => 'imooc_shop@163.com',
            //     'password'   => 'imooc123',
            //     'port'       => '465',
            //     'encryption' => 'ssl',
            // ],
            //
            //bug 修改时候需要注意删除冗余配置代码否则不对
            //2016年10月6日 10:43:30
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => require __DIR__ . '/db.php',
        /*
    'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    ],
    ],
     */
    ],
    'params'       => $params,
    'modules'      => [
        'admin' => [
            'class' => 'app\modules\admin',
        ],
    ],
    //模块配置文件2016年10月5日
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => [$_SERVER['REMOTE_ADDR']],
    ];
    // $config['modules']['admin'] = [
    //     'class' => 'app\modules\admin',
    // ];
}

return $config;

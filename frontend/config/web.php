<?php
$config = [
    'homeUrl'=>Yii::getAlias('@frontendUrl'),
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => '/user/sign-in/login',
    'modules' => [
        'user' => [
            'class' => 'frontend\modules\user\Module'
        ],
        'api' => [
            'class' => 'frontend\modules\api\Module',
            'modules' => [
                'v1' => 'frontend\modules\api\v1\Module'
            ]
        ],
        'mii' => [
            'class' => 'frontend\modules\mii\Module',
        ],

    ],
   
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => getenv('GITHUB_CLIENT_ID'),
                    'clientSecret' => getenv('GITHUB_CLIENT_SECRET')
                ]
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'request' => [
            'cookieValidationKey' => getenv('FRONTEND_COOKIE_VALIDATION_KEY')
        ],
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl'=>['/user/sign-in/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => 'common\behaviors\LoginTimestampBehavior'
        ],
        'sesMailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false, // Set this to false to send emails in a real environment
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'email-smtp.ap-south-1.amazonaws.com', // SES SMTP endpoint
                // 'username' => 'AKIAVYXG75TQDE2L7N3T',
                // 'password' => 'BJcOZvDsEqtx8Zm6LqwzhdS8ITRvUNEzsQEqTH1xqB1U',
                'username' => getenv('AWS_SES_ACCESS_KEY'),
                'password' => getenv('AWS_SES_SECRET_KEY'),
                'port' => 587, // Use 587 for TLS, 465 for SSL
                'encryption' => 'tls', // or 'ssl' if you're using port 465
            ],
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 7200, // 2 hours
            'cookieParams' => [
                'lifetime' => 7200, // 2 hours
            ],
        ],
        'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                'mii'
                /*'site/*',
                'admin/*',
               'sign-in/log-in',*/
            ],

        ],
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';  // Add debug to the bootstrap
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'], // Local and your network IP
    ];

    $config['bootstrap'][] = 'gii';  // Add gii to the bootstrap
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'], // Local and your network IP
    ];
}

if (YII_ENV_PROD) {
    // Maintenance mode
    $config['bootstrap'] = ['maintenance'];
    $config['components']['maintenance'] = [
        'class' => 'common\components\maintenance\Maintenance',
        'enabled' => function ($app) {
            return $app->keyStorage->get('frontend.maintenance') === 'enabled';
        }
    ];
}

return $config;

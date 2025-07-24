<?php
use \yii\web\Request;
$baseUrl = str_replace('/backend/web', '', (new Request)->getBaseUrl());
$config = [
    'on beforeRequest' => function ($event) {// to hide header in grid
        Yii::$container->set('yii\grid\DataColumn', [
        //    'class' => 'GroupColumnsBehavior',
            'headerOptions' => ['class' => 'abc'],
//            'filterInputOptions' => [
//                'placeholder' =>  $this->model->getAttributeLabel($this->attribute);
//            ],

        ]);
    },
    'homeUrl' => Yii::getAlias('@backendUrl'),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'user/index',
    'params' => [
        'icon-framework' => 'fa',  // Font Awesome Icon framework
    ],
    'aliases' => [
        '@mdm/admin' => '@vendor/yii2-admin-2.8',
        // for example: '@mdm/admin' => '@app/extensions/mdm/yii2-admin-2.0.0',

    ],
    'modules' => [

            'admin' => [
                'class' => 'mdm\admin\Module',

        ],
        'polling' => [
            'class' => 'backend\modules\polling\Module',
            'defaultRoute'=>'polling-quiz/index'
        ],

        'i18n' => [
            'class' => 'backend\modules\i18n\Module',
            'defaultRoute' => 'i18n-message/index'
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            'downloadAction' => '//gridview/export/download',
            // 'i18n' => []
        ],
        'mii' => [
            'class' => 'backend\modules\mii\Module',
            'defaultRoute'=>'default/custom-builder'
        ],
        'messageSystem' => [
            'class' => 'backend\modules\messagesystem\MessageSystem',
        ],
        'payroll' => [
            'class' => 'backend\modules\payroll\Payroll',
        ],
    ],
    'controllerMap' => [
        'file-manager-elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['manager'],
            'disabledCommands' => ['netmount'],
            'roots' => [
                [
                    'baseUrl' => '@storageUrl',
                    'basePath' => '@storage',
                    'path' => '/',
                    'access' => ['read' => 'manager', 'write' => 'manager']
                ]
            ]
        ]
    ],
    'components' => [
        // 'log' => [
        //     'traceLevel' => YII_DEBUG ? 3 : 0,
        //     'targets' => [
        //         [
        //             'class' => 'yii\log\FileTarget',
        //             'levels' => ['error', 'warning', 'info'], // Add 'info' for more details
        //             'logFile' => '@app/runtime/logs/app.log',
        //         ],
        //     ],
        // ],
    
        'google2fa' => [
            'class' => 'backend\components\Google2FAComponent',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager' // or use'yii\rbac\PhpManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        'request' => [
            'cookieValidationKey' => getenv('BACKEND_COOKIE_VALIDATION_KEY')
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl' => '@frontendUrl'.'/user/sign-in/login',
            'enableAutoLogin' => true,
            'as afterLogin' => 'common\behaviors\LoginTimestampBehavior'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => require(__DIR__ . '/../config/rules.php'),
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@frontend/mail',
            'useFileTransport' => false,//set this property to false to send mails to real email addresses

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
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
          //  'site',
            'polling/polling-quiz/play-quiz',
            'polling/polling-quiz/quiz-question-answer',
            'polling/polling-quiz/step',
            'polling/polling-quiz/get',
            'polling/polling-quiz/wizard',
            'polling/default/*',
            'default/*',
            'user/sign-in/request-password-reset',
            'sandbox/*',
            'system-log'
        ],

    ],
/*    'as globalAccess' => [
        'class' => '\common\behaviors\GlobalAccessBehavior',
        'rules' => [
            [
                'controllers' => ['sign-in'],
                'allow' => true,
                'roles' => ['?'],
                'actions' => ['login']
            ],
            [
                'controllers' => ['organisation'],
                'allow' => true,
                'roles' => ['organisation-admin'],
            ],
            [
                'controllers' => ['timeline-event'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'controllers' => ['client'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'controllers' => ['service'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'controllers' => ['receipt'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'controllers' => ['sign-in'],
                'allow' => true,
                'roles' => ['@'],
            ],

            [
                'controllers' => ['sign-in'],
                'allow' => true,
                'roles' => ['@'],
                'actions' => ['logout']
            ],
            [
                'controllers' => ['sign-up'],
                'allow' => true,
                'roles' => ['?'],
                'actions' => ['login']
            ],
            [
                'controllers' => ['site'],
                'allow' => true,
                'roles' => ['?', '@'],
                'actions' => ['error']
            ],
            [
                'controllers' => ['debug/default'],
                'allow' => true,
                'roles' => ['?'],
            ],
            [
                'controllers' => ['user'],
                'allow' => true,
                'roles' => ['administrator','organisation-admin'],
            ],
            [
                'controllers' => ['user'],
                'allow' => true,
                'roles' => ['@'],
                'actions' => ['unimpersonate']
            ],
            [
                'controllers' => ['user'],
                'allow' => true,
                'roles' => ['administrator'],
                'actions' => ['impersonate']
            ],
            [
                'controllers' => ['article'],
                'allow' => false,
                'roles' => ['administrator'],
            ],[
                'controllers' => ['article-request'],
                'actions' => ['available','revision'],
                'allow' => false,
                'roles' => ['administrator'],
            ],
            [
                'allow' => true,
                'roles' => ['manager'],
            ],
            [
                'allow' => true,
                'roles' => ['user'],
            ],
            [
                'allow' => true,
                'roles' => ['administrator'],
            ]
        ]
    ]*/
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
  //  $config['bootstrap'][] = 'debug';
  //  $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],
    ];
}

return $config;

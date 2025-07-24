<?php

$config = [

    'name'=>'Northman & Sterling',

    'vendorPath'=>dirname(dirname(__DIR__)).'/vendor',

    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),

    'sourceLanguage'=>'en-US',

    'language'=>'en-US',

    'bootstrap' => ['log'],

    'aliases' => [

        '@bower' => '@vendor/bower-asset',

        '@npm'   => '@vendor/npm-asset',

    ],

    'components' => [

        

        'authManager' => [

            'class' => 'yii\rbac\DbManager',

            'itemTable' => '{{%rbac_auth_item}}',

            'itemChildTable' => '{{%rbac_auth_item_child}}',

            'assignmentTable' => '{{%rbac_auth_assignment}}',

            'ruleTable' => '{{%rbac_auth_rule}}'

        ],



        'cache' => [

            'class' => 'yii\caching\DummyCache',

        ],



        'formatter'=>[

            'class'=>'yii\i18n\Formatter'

        ],



        'glide' => [

            'class' => 'trntv\glide\components\Glide',

            'sourcePath' => '@storage/web/source',

            'cachePath' => '@storage/cache',

            'urlManager' => 'urlManagerStorage',

            'maxImageSize' => getenv('GLIDE_MAX_IMAGE_SIZE'),

            'signKey' => getenv('GLIDE_SIGN_KEY')

        ],


// 'mailer' => [
//     'class' => 'yii\swiftmailer\Mailer',
//     'useFileTransport' => false, // Make sure it's FALSE for real emails
//     'messageConfig' => [
//         'charset' => 'UTF-8',
//         'from' => ['info.pangeaportal@gmail.com' => 'Leave Management System'],
//     ],
//     'transport' => [
//         'class' => 'Swift_SmtpTransport',
//         'host' => 'smtp.gmail.com',
//         'username' => 'info.pangeaportal@gmail.com',
//         'password' => 'vkwnuhkiabkfkxqj', // Use App Password if 2FA enabled
//         'port' => '587',
//         'encryption' => 'tls',
//     ],
// ],

'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false, // Make sure it's false to actually send emails
    'messageConfig' => [
        'charset' => 'UTF-8',
        'from' => ['notifications@myhr.northmansterling.com' => 'Leave Management System'],
    ],
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'server.urbanizehosting.com',
        'username' => 'notifications@myhr.northmansterling.com',
        'password' => 'WolvesWolves@2099',
        'port' => 465, // Change to 587 if you're using TLS
        'encryption' => 'ssl', // Change to 'tls' if using TLS and port 587
        
    ],
],




        'db'=>[

            'class'=>'yii\db\Connection',

            'dsn' => getenv('DB_DSN'),

            'username' => getenv('DB_USERNAME'),/*COMPARE WITH LIVE TO SEE THE LIVE DB VALUES.*/

            'password' => getenv('DB_PASSWORD'),

            'tablePrefix' => getenv('DB_TABLE_PREFIX'),

            'charset' => 'utf8',

            'enableSchemaCache' => YII_ENV_PROD,

        ],



        'log' => [

            'traceLevel' => YII_DEBUG ? 3 : 0,

            'targets' => [

                'db'=>[

                    'class' => 'yii\log\DbTarget',

                    'levels' => ['error', 'warning'],

                    'except'=>['yii\web\HttpException:*', 'yii\i18n\I18N\*'],

                    'prefix'=>function () {

                        $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;

                        return sprintf('[%s][%s]', Yii::$app->id, $url);

                    },

                    'logVars'=>[],

                    'logTable'=>'{{%system_log}}'

                ]

            ],

        ],



        'i18n' => [

            'translations' => [

                'app'=>[

                    'class' => 'yii\i18n\PhpMessageSource',

                    'basePath'=>'@common/messages',

                    'fileMap'=>[

                        'common'=>'common.php',

                        'backend'=>'backend.php',

                        'frontend'=>'frontend.php',

                    ]

                ],

                '*'=> [



                        'class' => 'yii\i18n\DbMessageSource',

                        'sourceMessageTable'=>'{{%i18n_source_message}}',

                        'messageTable'=>'{{%i18n_message}}',

                        'enableCaching' => YII_ENV_DEV,

                        'cachingDuration' => 3600,

//                        'on missingTranslation' => ['\backend\modules\i18n\Module', 'missingTranslation'],

                ],

                /* Uncomment this code to use DbMessageSource

                 '*'=> [

                    'class' => 'yii\i18n\DbMessageSource',

                    'sourceMessageTable'=>'{{%i18n_source_message}}',

                    'messageTable'=>'{{%i18n_message}}',

                    'enableCaching' => YII_ENV_DEV,

                    'cachingDuration' => 3600

                ],

                */



            ],

        ],


        'fileStorage' => [
            'class' => '\trntv\filekit\Storage',
            'baseUrl' => 'https://' . getenv('AWS_S3_BUCKET') . '.s3.' . getenv('AWS_REGION') . '.amazonaws.com',
            'filesystem' => [
                'class' => 'common\components\filesystem\AwsS3v3FlysystemBuilder',
                'key' => getenv('AWS_ACCESS_KEY'),
                'secret' => getenv('AWS_SECRET_KEY'),
                'region' => getenv('AWS_REGION'),
                'endPoint' => 'https://' . getenv('AWS_S3_BUCKET') . '.s3.' . getenv('AWS_REGION') . '.amazonaws.com/Generic'
            ],
            'as log' => [
                'class' => 'common\behaviors\FileStorageLogBehavior',
                'component' => 'fileStorage'
            ]
        ],

        // 'fileStorage' => [

        //     'class' => '\trntv\filekit\Storage',

        //     'baseUrl' => '@storageUrl/source',

        //     'filesystem' => [

        //         'class' => 'common\components\filesystem\LocalFlysystemBuilder',

        //         'path' => '@storage/web/source'

        //     ],

        //     'as log' => [

        //         'class' => 'common\behaviors\FileStorageLogBehavior',

        //         'component' => 'fileStorage'

        //     ]

        // ],



        'keyStorage' => [

            'class' => 'common\components\keyStorage\KeyStorage'

        ],



        'urlManagerBackend' => \yii\helpers\ArrayHelper::merge(

            [

                'hostInfo' => Yii::getAlias('@backendUrl')

            ],

            require(Yii::getAlias('@backend/config/_urlManager.php'))

        ),

        'urlManagerFrontend' => \yii\helpers\ArrayHelper::merge(

            [

                'hostInfo'=>Yii::getAlias('@frontendUrl')

            ],

            require(Yii::getAlias('@frontend/config/_urlManager.php'))

        ),

        'urlManagerStorage' => \yii\helpers\ArrayHelper::merge(

            [

                'hostInfo'=>Yii::getAlias('@storageUrl')

            ],

            require(Yii::getAlias('@storage/config/_urlManager.php'))

        )

    ],

    'params' => [

        'adminEmail' => getenv('ADMIN_EMAIL'),

        'robotEmail' => getenv('ROBOT_EMAIL'),

        'availableLocales'=>[

            'en-US'=>'English (US)',

            'ar-AE'=>'Arabic',

            'es' => 'Español'

        ],

        'translationLocales'=>[

            'ar-AE'=>'Arabic',

            'es' => 'Español'

        ],



    ],

];



if (YII_ENV_PROD) {

    $config['components']['cache'] = [

        'class' => 'yii\caching\FileCache',

        'cachePath' => '@common/runtime/cache'

    ];



//    $config['components']['log']['targets']['email'] = [

//        'class' => 'yii\log\EmailTarget',

//        'except' => ['yii\web\HttpException:*'],

//        'levels' => ['error', 'warning'],

//        'message' => ['from' => getenv('ROBOT_EMAIL'), 'to' => getenv('ADMIN_EMAIL')]

//    ];

}



if (YII_ENV_DEV) {

    $config['bootstrap'][] = 'gii';

    $config['modules']['gii'] = [

        'class'=>'yii\gii\Module'

    ];

}



return $config;


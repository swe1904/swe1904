<?php
return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap'=>[
        'message'=>[
            'class'=>'console\controllers\ExtendedMessageController'
        ],
        'migrate'=>[
            'class'=>'yii\console\controllers\MigrateController',
            'migrationPath'=>'@common/migrations',
            'migrationTable'=>'{{%system_migration}}'
        ],
        'rbac'=>[
            'class'=>'console\controllers\RbacController'
        ]
    ],
    'components' => [
        'db'=>[
            'class'=>'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),/*COMPARE WITH LIVE TO SEE THE LIVE DB VALUES.*/
            'password' => getenv('DB_PASSWORD'),
            'tablePrefix' => getenv('DB_TABLE_PREFIX'),
            'charset' => 'utf8',
            'enableSchemaCache' => YII_ENV_PROD,
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
    ],
];

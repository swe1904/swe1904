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
    ],
];

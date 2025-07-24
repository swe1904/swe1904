<?php
return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
//    'layout' => 'main_pangea',
  //  'layout' => 'main',
    'layout'=> 'main_pangea_final',
    'components' => [
        'urlManager'=>require(__DIR__.'/_urlManager.php'),
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

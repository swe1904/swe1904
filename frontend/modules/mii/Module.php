<?php

namespace frontend\modules\mii;

class Module extends \yii\gii\Module
{
    public $controllerNamespace = 'frontend\modules\mii\controllers';

    public $generators = [];

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    protected function coreGenerators()
    {
        return [
            'model' => ['class' => 'frontend\modules\mii\generators\model\Generator'],
            'crud' => ['class' => 'frontend\modules\mii\generators\crud\Generator'],
            'controller' => ['class' => 'yii\gii\generators\controller\Generator'],
            'form' => ['class' => 'yii\gii\generators\form\Generator'],
            'module' => ['class' => 'yii\gii\generators\module\Generator'],
            'extension' => ['class' => 'yii\gii\generators\extension\Generator'],
        ];
    }
}

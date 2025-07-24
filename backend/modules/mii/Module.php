<?php

namespace backend\modules\mii;
use Yii;
use yii\web\ForbiddenHttpException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\mii\controllers';

    public $generators = [];
    /**
     * @var integer the permission to be set for newly generated code files.
     * This value will be used by PHP chmod function.
     * Defaults to 0666, meaning the file is read-writable by all users.
     */
    public $newFileMode = 0666;
    /**
     * @var integer the permission to be set for newly generated directories.
     * This value will be used by PHP chmod function.
     * Defaults to 0777, meaning the directory can be read, written and executed by all users.
     */
    public $newDirMode = 0777;

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        /*if (Yii::$app instanceof \yii\web\Application && !$this->checkAccess()) {
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        }*/

        foreach (array_merge($this->coreGenerators(), $this->generators) as $id => $config) {
            $this->generators[$id] = Yii::createObject($config);
        }

        $this->resetGlobalSettings();

        return true;
    }

    /**
     * Resets potentially incompatible global settings done in app config.
     */
    protected function resetGlobalSettings()
    {
        if (Yii::$app instanceof \yii\web\Application) {
            Yii::$app->assetManager->bundles = [];
        }
    }

    protected function coreGenerators()
    {
        return [
            'model' => ['class' => 'backend\modules\mii\generators\model\Generator'],
            'crud' => ['class' => 'backend\modules\mii\generators\crud\Generator'],
            'controller' => ['class' => 'backend\modules\mii\generators\controller\Generator'],
            'form' => ['class' => 'backend\modules\mii\generators\form\Generator'],
            'module' => ['class' => 'backend\modules\mii\generators\module\Generator'],
            'extension' => ['class' => 'backend\modules\mii\generators\extension\Generator'],
        ];
    }
}

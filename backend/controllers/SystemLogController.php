<?php
namespace backend\controllers;

use backend\models\SystemLog;
use common\components\keyStorage\FormModel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SystemLogController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = Yii::$app->user->isGuest ? 'base' : 'common_pangea_final';
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $query = SystemLog::find();
        $query->orderBy(['log_time' => SORT_DESC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
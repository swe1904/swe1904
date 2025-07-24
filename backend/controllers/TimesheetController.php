<?php

namespace backend\controllers;
use Yii;
use backend\models\Timesheet;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class TimesheetController extends Controller
{
    public function actionIndex()
    {
        $query = Timesheet::find();

        if ($employeeId = Yii::$app->request->get('employee_id')) {
            $query->andWhere(['employee_id' => $employeeId]);
        }

        if ($date = Yii::$app->request->get('date')) {
            $query->andWhere(['date' => $date]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Timesheet();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
<?php
namespace backend\controllers;

use Yii;
use backend\models\PayrollRun;
use backend\models\PayrollDetails;
use backend\models\Employee;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PayrollController extends Controller
{
    public function actionIndex()
    {
        // $payrolls = PayrollRun::find()->all();
        return $this->render('index');
    }

    public function actionCreate()
    {
        $model = new PayrollRun();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['process', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionProcess($id)
    {
        $payrollRun = PayrollRun::findOne($id);
        $employees = Employee::find()->all();

        return $this->render('process', [
            'payrollRun' => $payrollRun,
            'employees' => $employees,
        ]);
    }

    public function actionFinalize($id)
    {
        $payrollRun = PayrollRun::findOne($id);
        if ($payrollRun) {
            $payrollRun->status = 'Finalized';
            $payrollRun->save();
        }

        return $this->redirect(['index']);
    }
}

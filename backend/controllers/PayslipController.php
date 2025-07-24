<?php
namespace backend\controllers;

use backend\models\Employee;
use backend\models\PayrollRun;
use Yii;
use backend\models\Payslip;
use backend\models\search\PayslipSearch;
use Mpdf\Mpdf;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
class PayslipController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
    
        $payslips = Payslip::find()
            ->where(['employee_id' => $userId]) // Adjust if you use `user_id` instead
            ->orderBy(['payslip_date' => SORT_DESC])
            ->all();
    
        return $this->render('index', [
            'payslips' => $payslips,
        ]);
    }

public function actionDownload($id)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $payslip = Payslip::findOne($id);

    if (!$payslip) {
        throw new \yii\web\NotFoundHttpException('Payslip not found.');
    }

    // Optional: If only employees should download their own payslips
    // if (Yii::$app->user->identity->role == 'employee') {
    //     if ($payslip->employee_id != Yii::$app->user->id) {
    //         throw new \yii\web\ForbiddenHttpException('Access denied.');
    //     }
    // }

    // Extract payroll_month and payroll_year from payslip_date
    $date = strtotime($payslip->payslip_date);
   $month = date('n', $date);
    $year = date('Y', $date);

    // File name should match exactly how it's saved during generation
    $fileName = "Payslip_{$payslip->employee_id}_{$month}_{$year}.pdf";
    $filePath = Yii::getAlias('@backend') . "/web/payslips/" . $fileName;

    if (file_exists($filePath)) {
        return Yii::$app->response->sendFile($filePath, $fileName);
    } else {
        Yii::$app->session->setFlash('error', 'Payslip file does not exist.');
        return $this->redirect(['index']);
    }
}



    public function actionPayslipHistory()
{
    $userId = Yii::$app->user->id;
    $employeeId = Employee::find()->select('id')->where(['user_id' => $userId])->scalar();

    $dataProvider = new ActiveDataProvider([
        'query' => Payslip::find()->where(['employee_id' => $employeeId])->orderBy(['payslip_date' => SORT_DESC]),
        'pagination' => ['pageSize' => 10],
    ]);

    return $this->render('history', [
        'dataProvider' => $dataProvider
    ]);
}

    

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Payslip();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    public function actionDownloadPdf($id)
    {
        $model = Payslip::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Payslip not found.");
        }

        // Get Employee Details
        $employee = Employee::findOne($model->employee_id);

        // Generate PDF Content
        $html = $this->renderPartial('pdf', [
            'model' => $model,
            'employee' => $employee,
        ]);

        // Generate PDF using mPDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('Payslip_' . $employee->first_name . '_' . $model->pay_period . '.pdf', 'D'); // Download PDF
    }

    public function actionDownload_old($id)
    {
        $payslip = Payslip::findOne($id);
        $mpdf = new Mpdf();
        $mpdf->WriteHTML("
            <h1>Payslip</h1>
            <p>Employee: {$payslip->employee->first_name} {$payslip->employee->last_name}</p>
            <p>Net Salary: {$payslip->net_salary}</p>
        ");
        $mpdf->Output("Payslip_{$payslip->id}.pdf", 'D');
    }
    protected function findModel($id)
    {
        if (($model = Payslip::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

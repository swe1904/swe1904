<?php
namespace backend\controllers;
use Yii;
// File: controllers/EmployeeAttendanceController.php


use yii\web\Controller;
use yii\web\Response;
use backend\models\AttendanceLog;
use backend\models\ShiftSchedule;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class EmployeeAttendanceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['clock-in', 'clock-out', 'dashboard'],
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'clock-in' => ['POST'],
                    'clock-out' => ['POST'],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        $userId = Yii::$app->user->id;
        $today = date('Y-m-d');
        $log = AttendanceLog::findOne(['employee_id' => $userId, 'date' => $today]);
        $shift = ShiftSchedule::findOne(['employee_id' => $userId]);

        return $this->render('dashboard', [
            'log' => $log,
            'shift' => $shift,
        ]);
    }
public function actionIndex()
{
    $dataProvider = new ActiveDataProvider([
        'query' => AttendanceLog::find()->with('employee'), // assuming relation
        'pagination' => ['pageSize' => 20],
    ]);

    return $this->render('index', ['dataProvider' => $dataProvider]);
}
public function actionCreate()
{
    $model = new AttendanceLog();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('create', ['model' => $model]);
}
    public function actionClockIn()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $today = date('Y-m-d');

        $log = AttendanceLog::findOne(['employee_id' => $userId, 'date' => $today]);
        if ($log && $log->clock_in_time) {
            return ['status' => 'already_clocked_in'];
        }

        $log = $log ?: new AttendanceLog();
        $log->employee_id = $userId;
        $log->date = $today;
        $log->clock_in_time = date('Y-m-d H:i:s');
        $log->ip_address = Yii::$app->request->userIP;
        $log->device_type = Yii::$app->request->userAgent;
        $log->location_status = 'inside_geofence'; // placeholder

        if ($log->save()) {
            return ['status' => 'clocked_in'];
        }

        return ['status' => 'error', 'errors' => $log->errors];
    }

    public function actionClockOut()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $today = date('Y-m-d');

        $log = AttendanceLog::findOne(['employee_id' => $userId, 'date' => $today]);

        if (!$log || !$log->clock_in_time) {
            return ['status' => 'not_clocked_in'];
        }

        if ($log->clock_out_time) {
            return ['status' => 'already_clocked_out'];
        }

        $log->clock_out_time = date('Y-m-d H:i:s');
        $log->worked_minutes = (strtotime($log->clock_out_time) - strtotime($log->clock_in_time)) / 60;

        if ($log->save()) {
            return ['status' => 'clocked_out', 'worked_minutes' => $log->worked_minutes];
        }

        return ['status' => 'error', 'errors' => $log->errors];
    }
}

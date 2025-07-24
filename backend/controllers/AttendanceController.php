<?php
namespace backend\controllers;
use Yii;
use backend\models\Attendance;
use backend\models\Employee;
use backend\models\search\AttendanceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;

class AttendanceController extends Controller
{
    // public $layout = '@backend/views/layouts/common.php';

    public function behaviors()
    {
    return [
    'verbs' => [
    'class' => VerbFilter::class,
    'actions' => ['delete' => ['POST']],
    ],
    ];
    }

    public function actionIndex()
    {
    $searchModel = new AttendanceSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    return $this->render('index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    ]);
    }

    public function actionCreate()
    {
    $model = new Attendance();
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
    return $this->redirect(['index']);
    }
    return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
    $model = $this->findModel($id);
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
    return $this->redirect(['index']);
    }
    return $this->render('update', ['model' => $model]);
    }

    public function actionView($id)
    {
    $model = Attendance::findOne($id);

    if (!$model) {
    throw new \yii\web\NotFoundHttpException("The requested attendance record does not exist.");
    }

    return $this->render('view', [
    'model' => $model,
    ]);
    }

    // public function actionCheckIn()
    // {
    // $userId = Yii::$app->user->id;
    // $employee = \backend\models\Employee::findOne(['user_id' => $userId]);

    // if (!$employee) {
    // throw new \yii\web\NotFoundHttpException("Employee not found.");
    // }

    // $today = date('Y-m-d');

    // // Check if already checked in today
    // $existing = \backend\models\Attendance::findOne(['employee_id' => $employee->user_id, 'date' => $today]);
    // if ($existing) {
    // Yii::$app->session->setFlash('info', 'You have already checked in today.');
    // return $this->redirect(['index']);
    // }

    // // Get coordinates from POST
    // $latitude = Yii::$app->request->post('latitude');
    // $longitude = Yii::$app->request->post('longitude');

    // // Create new attendance record
    // $attendance = new \backend\models\Attendance();
    // $attendance->employee_id = $employee->user_id;
    // $attendance->date = $today;
    // $attendance->in_time = date('H:i:s');
    // $attendance->checkin_lat = $latitude;
    // $attendance->checkin_lng = $longitude;

    // // Optional: mark late if after 9 AM
    // $checkInHour = date('H');
    // $attendance->status = ($checkInHour >= 9) ? 'Late' : 'Present';

    // if ($attendance->save(false)) {
    // Yii::$app->session->setFlash('success', 'Checked in successfully with location.');
    // } else {
    // Yii::$app->session->setFlash('error', 'Check-in failed. Please try again.');
    // }

    // return $this->redirect(['index']);
    // }
// public function actionCheckIn()
// {
//     $userId = Yii::$app->user->id;
//     $employee = \backend\models\Employee::findOne(['user_id' => $userId]);

//     if (!$employee) {
//         Yii::$app->session->setFlash('warning', 'Your profile is not found in the Employee list. Please contact HR to get added.');
//         return $this->redirect(['index']);
//     }

//     $today = date('Y-m-d');

//     $existing = \backend\models\Attendance::findOne([
//         'employee_id' => $employee->user_id,
//         'date' => $today
//     ]);

//     if ($existing) {
//         Yii::$app->session->setFlash('info', 'You have already checked in today.');
//         return $this->redirect(['index']);
//     }

//     // Get coordinates from POST
//     $latitude = Yii::$app->request->post('latitude');
//     $longitude = Yii::$app->request->post('longitude');

//     $attendance = new \backend\models\Attendance();
//     $attendance->employee_id = $employee->user_id; // ✅ fixed this (was incorrectly using $employee->user_id)
//     $attendance->date = $today;
//     $attendance->in_time = date('H:i:s');
//     $attendance->checkin_lat = $latitude;
//     $attendance->checkin_lng = $longitude;

//     // Mark as Late if checking in after 9 AM
//     $checkInHour = date('H');
//     $attendance->status = ($checkInHour >= 9) ? 'Late' : 'Present';

//     if ($attendance->save(false)) {
//         Yii::$app->session->setFlash('success', 'Checked in successfully with location.');
//         Yii::$app->session->set('screenshot_active', true);
//     } else {
//         Yii::$app->session->setFlash('error', 'Check-in failed. Please try again.');
//     }

//     return $this->redirect(['index']);
// }
public function actionCheckIn()
{
    $userId = Yii::$app->user->id;
    $employee = \backend\models\Employee::findOne(['user_id' => $userId]);

    if (!$employee) {
        Yii::$app->session->setFlash('warning', 'Your profile is not found in the Employee list. Please contact HR to get added.');
        return $this->redirect(['index']);
    }

    // Get timezone data from POST
    $timezoneOffset = Yii::$app->request->post('timezone_offset'); // in minutes
    $localTimeISO   = Yii::$app->request->post('local_time');      // ISO string

    if (empty($timezoneOffset) || empty($localTimeISO)) {
        Yii::$app->session->setFlash('error', 'Timezone information is missing. Please allow location access and try again.');
        return $this->redirect(['index']);
    }

    // Convert local time to UTC (subtract offset)
    try {
        $localTime = new \DateTime($localTimeISO);
        $offset = (int)$timezoneOffset; // e.g. -330
        $localTime->modify("-{$offset} minutes");

        $inTimeUTC = $localTime->format('H:i:s');
        $dateUTC = $localTime->format('Y-m-d');
    } catch (\Exception $e) {
        Yii::$app->session->setFlash('error', 'Invalid time format.');
        return $this->redirect(['index']);
    }

    // Prevent duplicate check-in
    $existing = \backend\models\Attendance::findOne([
        'employee_id' => $employee->user_id,
        'date' => $dateUTC,
    ]);

    if ($existing) {
        Yii::$app->session->setFlash('info', 'You have already checked in today.');
        return $this->redirect(['index']);
    }

    // Get coordinates
    $latitude = Yii::$app->request->post('latitude');
    $longitude = Yii::$app->request->post('longitude');

    // Save attendance
    $attendance = new \backend\models\Attendance();
    $attendance->employee_id = $employee->user_id;
    $attendance->date = $dateUTC;
    $attendance->in_time = $inTimeUTC;
    $attendance->checkin_lat = $latitude;
    $attendance->checkin_lng = $longitude;

    // Mark as Late if after 9:00 AM UTC
    $checkInHour = (int)(new \DateTime($inTimeUTC))->format('H');
    $attendance->status = ($checkInHour >= 9) ? 'Late' : 'Present';

    if ($attendance->save(false)) {
        Yii::$app->session->setFlash('success', 'Checked in successfully.');
        Yii::$app->session->set('screenshot_active', true);
    } else {
        Yii::$app->session->setFlash('error', 'Check-in failed. Please try again.');
    }

    return $this->redirect(['index']);
}

public function actionCheckOut()
{
    $userId = Yii::$app->user->id;
    $employee = \backend\models\Employee::findOne(['user_id' => $userId]);

    if (!$employee) {
        throw new \yii\web\NotFoundHttpException("Employee not found.");
    }

    // Get timezone data
    $timezoneOffset = Yii::$app->request->post('timezone_offset'); // in minutes
    $localTimeISO   = Yii::$app->request->post('local_time');      // ISO string

    if (empty($timezoneOffset) || empty($localTimeISO)) {
        Yii::$app->session->setFlash('error', 'Timezone info missing. Please allow location/time access.');
        return $this->redirect(['index']);
    }

    // Convert local time to UTC
    try {
        $localTime = new \DateTime($localTimeISO);
        $offset = (int)$timezoneOffset;
        $localTime->modify("-{$offset} minutes");

        $outTimeUTC = $localTime->format('H:i:s');
        $dateUTC = $localTime->format('Y-m-d');
    } catch (\Exception $e) {
        Yii::$app->session->setFlash('error', 'Invalid local time format.');
        return $this->redirect(['index']);
    }

    // Load today's attendance by employee_id and date
    $attendance = \backend\models\Attendance::findOne([
        'employee_id' => $employee->user_id,
        'date' => $dateUTC
    ]);

    if (!$attendance) {
        Yii::$app->session->setFlash('error', 'You need to check in first.');
        return $this->redirect(['index']);
    }

    if ($attendance->out_time !== null) {
        Yii::$app->session->setFlash('info', 'You have already checked out today.');
        return $this->redirect(['index']);
    }

    $currentLat = Yii::$app->request->post('latitude');
    $currentLng = Yii::$app->request->post('longitude');

    if (!$currentLat || !$currentLng) {
        Yii::$app->session->setFlash('error', 'Location not found. Please enable GPS.');
        return $this->redirect(['index']);
    }

    if (!$attendance->checkin_lat || !$attendance->checkin_lng) {
        Yii::$app->session->setFlash('error', 'Check-in location not recorded.');
        return $this->redirect(['index']);
    }

    $distance = $this->calculateDistance(
        $attendance->checkin_lat,
        $attendance->checkin_lng,
        $currentLat,
        $currentLng
    );

    Yii::info("Check-out distance = {$distance} meters", __METHOD__);

    if ($distance > 25) {
        Yii::$app->session->setFlash('error', 'Check-out location must be within 25 meters of check-in location.');
        return $this->redirect(['index']);
    }

    // Save checkout
    $attendance->out_time = $outTimeUTC;
    $attendance->checkout_lat = $currentLat;
    $attendance->checkout_lng = $currentLng;
    $attendance->save(false);

    Yii::$app->session->setFlash('success', '✅ Checked out successfully.');
    Yii::$app->session->remove('screenshot_active');

    return $this->redirect(['index']);
}



private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // meters

    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

    return $earthRadius * $angle;
}


    protected function findModel($id)
    {
        if (($model = Attendance::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //     public function actionUploadScreenshot()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     if (Yii::$app->request->isPost && isset($_FILES['screenshot'])) {
    //         $file = $_FILES['screenshot'];

    //         $path = Yii::getAlias('@webroot/uploads/screenshots');
    //         if (!is_dir($path)) {
    //             mkdir($path, 0777, true);
    //         }

    //         $userId = Yii::$app->user->id;
    //         $filename = 'screenshot_' . $userId . '_' . time() . '.png';
    //         $fullPath = $path . '/' . $filename;

    //         if (move_uploaded_file($file['tmp_name'], $fullPath)) {
    //             return ['status' => 'success', 'filename' => $filename];
    //         }
    //     }

    //     return ['status' => 'error'];
    // }

    public function actionUploadScreenshot()
    {
    $uploadedFile = UploadedFile::getInstanceByName('screenshot');

    if ($uploadedFile) {
        $path = Yii::getAlias('@webroot/uploads/screenshots/');
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $filename = 'screenshot_' . time() . '.' . $uploadedFile->extension;
        $uploadedFile->saveAs($path . $filename);

        return json_encode(['status' => 'success', 'filename' => $filename]);
    }

    return json_encode(['status' => 'error', 'message' => 'No screenshot found']);
    }


    public function actionTestScreenshotUpload()
    {
    $dummy = imagecreatetruecolor(100, 100);
    imagefill($dummy, 0, 0, imagecolorallocate($dummy, 255, 0, 0)); // Red image
    $filename = 'test_' . time() . '.png';
    $path = Yii::getAlias('@webroot/uploads/screenshots/') . $filename;
    imagepng($dummy, $path);
    imagedestroy($dummy);
    echo "✅ Dummy screenshot saved to: $filename";
    }



}

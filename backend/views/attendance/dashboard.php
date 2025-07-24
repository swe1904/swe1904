<?php

use backend\models\Attendance;
use backend\models\Employee;
use yii\helpers\Html;

$today = date('Y-m-d');
$userId = Yii::$app->user->id;
$employee = Employee::findOne(['user_id' => $userId]);
$attendance = Attendance::findOne(['employee_id' => $employee->id, 'date' => $today]);
?>

<?php if (!$attendance): ?>
    <?= Html::a('Check In', ['attendance/check-in'], ['class' => 'btn btn-success']) ?>
<?php elseif ($attendance && $attendance->check_out_time === null): ?>
    <?= Html::a('Check Out', ['attendance/check-out'], ['class' => 'btn btn-danger']) ?>
<?php else: ?>
    <p>You have already checked in and out today.</p>
<?php endif; ?>

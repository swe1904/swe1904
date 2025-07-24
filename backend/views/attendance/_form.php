<?php

use backend\models\Attendance;
use backend\models\Employee;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$today = date('Y-m-d');
$userId = Yii::$app->user->id;

$employee = Employee::findOne(['user_id' => $userId]);

if ($employee !== null) {
    $attendance = Attendance::findOne([
        'employee_id' => $employee->user_id,
        'date' => $today
    ]);
} else {
    Yii::$app->session->setFlash('error', 'Employee profile not found for current user.');
    $attendance = null;
}
?>

<style>
.attendance-panel {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 12px;
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
}
.attendance-panel h4 {
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 15px;
}
.attendance-panel p {
    font-size: 15px;
    margin: 6px 0;
}
.attendance-panel strong {
    font-weight: bold;
}
@media screen and (max-width: 768px) {
    .attendance-panel {
        padding: 20px;
        font-size: 15px;
    }
    .btn {
        width: 100% !important;
        font-size: 16px;
        padding: 10px;
    }
    .attendance-panel h4 {
        font-size: 17px;
    }
}
</style>

<div class="attendance-panel">
    <h4>📅 Today's Attendance - <?= date('d M Y') ?></h4>

    <?php if (!$attendance): ?>
        <p><strong>Status:</strong> Not yet checked in.</p>
        <div id="status" class="alert alert-info">📍 Detecting location...</div>

        <?php $form = ActiveForm::begin(['action' => ['attendance/check-in'], 'method' => 'post', 'id' => 'checkin-form']); ?>
            <?= Html::hiddenInput('latitude', '', ['id' => 'latitude']) ?>
            <?= Html::hiddenInput('longitude', '', ['id' => 'longitude']) ?>
            <?= Html::hiddenInput('local_time', '', ['id' => 'local_time']) ?>
            <?= Html::hiddenInput('timezone_offset', '', ['id' => 'timezone_offset']) ?>

            <div id="checkin-btn" style="display: none; margin-top: 10px;">
                <?= Html::submitButton('✅ Check In (with Location)', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const accuracy = position.coords.accuracy;

                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;

                        const now = new Date();
                        document.getElementById('local_time').value = now.toISOString();
                        document.getElementById('timezone_offset').value = now.getTimezoneOffset();

                        const statusBox = document.getElementById('status');
                        if (accuracy > 100) {
                            statusBox.className = 'alert alert-warning';
                            statusBox.innerHTML = `⚠️ Location accuracy is low (${Math.round(accuracy)} m). Proceeding anyway.`;
                        } else {
                            statusBox.className = 'alert alert-success';
                            statusBox.innerHTML = `✅ Location captured (${Math.round(accuracy)} m accuracy).`;
                        }

                        document.getElementById('checkin-btn').style.display = 'block';
                    },
                    function (error) {
                        document.getElementById('status').className = 'alert alert-danger';
                        document.getElementById('status').innerHTML = '❌ Failed to get location: ' + error.message;
                    }
                );
            } else {
                document.getElementById('status').className = 'alert alert-danger';
                document.getElementById('status').innerHTML = '❌ Geolocation is not supported by this browser.';
            }
        });
        </script>
    <?php else: ?>
        <p><strong>Status:</strong> ✅ Already Checked In</p>
        <p><strong>Date:</strong> 📅 <?= date('d M Y', strtotime($attendance->date)) ?></p>
        <p><strong>In Time:</strong> ⏰ <?= $attendance->in_time ?? 'N/A' ?></p>

        <?php if ($attendance->out_time === null): ?>
            <?php $form = ActiveForm::begin(['action' => ['attendance/check-out'], 'method' => 'post']); ?>
                <?= Html::submitButton('🔚 Check Out', ['class' => 'btn btn-danger', 'style' => 'margin-top:10px']) ?>
            <?php ActiveForm::end(); ?>
        <?php else: ?>
            <p><strong>Out Time:</strong> 🕒 <?= $attendance->out_time ?></p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php

use backend\models\Department;
use backend\models\Employee;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\LeaveRequest */

$this->title = 'Update Leave Request';
$this->params['breadcrumbs'][] = ['label' => 'Leave Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

// Fetch dynamic data
$user = Yii::$app->user->identity;
$userName = $user->username ?? 'Unknown';
$userId = Yii::$app->user->id;

// Find the employee based on the user_id

// Find employee by user ID
$employee = \backend\models\Employee::findOne(['user_id' => $userId]);

$preferredFullName = $employee->preferred_full_name ?? 'N/A';
$remainingLeaveDays = $employee->annual_leave ?? 'N/A';

// Fetch department and department manager
$department = $employee ? \backend\models\Department::findOne($employee->department_id) : null;
$departmentManagerId = $department->department_manager ?? null;

$manager = $departmentManagerId ? \common\models\User::findOne($departmentManagerId) : null;
$departmentManager = $manager->username ?? 'N/A'; // You can also use $manager->fullname if needed

// Optional: Fetch team manager if needed
$teamManager = 'N/A';
$teamManagerId = null;
if ($employee && $employee->team) {
    $team = \backend\models\Team::findOne($employee->team); // Check your actual table name
    if ($team && $team->team_manager) {
        $teamManagerUser = \common\models\User::findOne($team->team_manager);
        $teamManager = $teamManagerUser->fullname ?? 'N/A';
        $teamManagerId = $teamManagerUser->id ?? null;
    }
}
?>

<div class="leave-request-update">
    <?php $form = ActiveForm::begin(['id' => 'leave-request-form']); ?>

    <!-- Ribbon Information Section -->
    <div class="ribbon">
        <span><b>Information:</b></span>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Preferred Full Name:</label><span style="margin-left: 10px;"><?= Html::encode($preferredFullName) ?></span><br>
            <label>Department Manager:</label><span style="margin-left: 10px;"><?= Html::encode($departmentManager) ?></span><br>
            <label>Remaining Leave Days:</label><span style="margin-left: 10px;"><?= Html::encode($remainingLeaveDays) ?></span><br>
            <?= Html::hiddenInput('department_manager', $departmentManagerId); ?>
        </div>
    </div>

    <h4><b><?= Html::encode($this->title) ?></b></h4>

    <div class="form-row">
        <?= $form->field($model, 'employee_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
    </div>

    <!-- Leave Request Fields -->
    <div class="form-row">
        <div class="form-group col-md-3">
            <?= $form->field($model, 'leave_type')->dropDownList([
                'Paid Annual Leave' => 'Paid Annual Leave',
                'Leave Without Pay' => 'Leave Without Pay',
                'Sick Leave' => 'Sick Leave',
                'Study Leave' => 'Study Leave',
                'Compassionate Leave' => 'Compassionate Leave',
                'Maternity Leave' => 'Maternity Leave',
                'Paternity Leave' => 'Paternity Leave',
            ], ['class' => 'form-control', 'prompt' => 'Select Leave Type'])->label('Leave Type <span class="text-danger">*</span>') ?>
        </div>

        <div class="form-group col-md-3">
           <?= $form->field($model, 'start_date')->widget(DatePicker::class, [
    'options' => [
        'class' => 'form-control',
        'id' => 'leave-request-start_date', // ✅ Set ID manually
    ],
    'dateFormat' => 'yyyy-MM-dd',
    'clientOptions' => [
        'changeMonth' => true,
        'changeYear' => true,
        'yearRange' => '1950:2050',
        // 'minDate' => 0,
    ],
])->label('Start Date (First Day Off) <span class="text-danger">*</span>', ['encode' => false]) ?>
        </div>

        <div class="form-group col-md-3">
            <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
    'options' => [
        'class' => 'form-control',
        'id' => 'leave-request-end_date', // ✅ Set ID manually
    ],
    'dateFormat' => 'yyyy-MM-dd',
    'clientOptions' => [
        'changeMonth' => true,
        'changeYear' => true,
        'yearRange' => '1950:2050',
    ],
])->label('End Date (Last Day Off) <span class="text-danger">*</span>', ['encode' => false]) ?>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-row">
        <div class="form-group col-md-4">
            <?= Html::submitButton('Update Leave Request', ['class' => 'btn btn-primary', 'id' => 'submit-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerCss(" 
    .ribbon {
        display: flex;
        justify-content: space-between;
        background:rgb(39, 38, 38);
        color: white;
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: space-between;
    }

    .form-group {
        flex: 1 1 22%;
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
    }

    .btn {
        width: auto;
    }

    .text-danger {
        color: red;
    }

    @media (max-width: 767px) {
        .form-group {
            flex: 1 1 100%;
        }
        .ribbon {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
");
?>

<script>
$(document).ready(function() {
    $('#submit-button').on('click', function(event) {
        var startDate = $('#leave-request-start_date').val();
        var endDate = $('#leave-request-end_date').val();

        if (!startDate || !endDate) {
            alert('Both start and end dates must be filled.');
            return false;
        }

        var startDateObj = new Date(startDate);
        var endDateObj = new Date(endDate);

        if (startDateObj > endDateObj) {
            alert('End date cannot be before the start date.');
            return false;
        }
    });
});
</script>


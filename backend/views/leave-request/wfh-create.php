<?php

use backend\models\Department;
use backend\models\Employee;
use backend\models\Team;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\jui\DatePicker;

$this->title = 'Request Work From Home';
$this->params['breadcrumbs'][] = ['label' => 'Leave Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get current user
$user = Yii::$app->user->identity;
$userId = $user->id ?? null;

// Get employee
$employee = Employee::findOne(['user_id' => $userId]);

$preferredFullName = $employee->preferred_full_name ?? 'N/A';
$remainingLeaveDays = $employee->annual_leave ?? 'N/A';

// Get department manager
$departmentManager = 'N/A';
$departmentManagerId = null;
if ($employee && $employee->department_id) {
    $department = Department::findOne($employee->department_id);
    if ($department && $department->department_manager) {
        $managerUser = User::findOne($department->department_manager);
        $departmentManager = $managerUser->username ?? 'N/A';
        $departmentManagerId = $managerUser->id ?? null;
    }
}
?>

<div class="leave-request-create">
    <?php $form = ActiveForm::begin(['id' => 'leave-request-form']); ?>

    <!-- Ribbon Information Section -->
    <div class="ribbon">
        <span><b>Information</b></span>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Preferred Full Name:</label>
            <span class="form-value"><?= Html::encode($preferredFullName) ?></span><br>
            <label>Department Manager:</label>
            <span class="form-value"><?= Html::encode($departmentManager) ?></span>
            <?= Html::hiddenInput('department_manager', $departmentManagerId); ?>
        </div>
    </div>

    <h4 class="my-3"><b><?= Html::encode($this->title) ?></b></h4>

    <?= $form->field($model, 'employee_id')->hiddenInput(['value' => $userId])->label(false) ?>
    <?= $form->field($model, 'leave_type')->hiddenInput(['value' => 'WFH'])->label(false) ?>

    <div class="form-row">
        <div class="form-group col-md-3">
            <?= $form->field($model, 'notes')->textarea([
                'rows' => 4,
                'placeholder' => 'Reason for requesting WFH...',
                'class' => 'form-control small-textarea',
            ])->label('Reason / Notes <span class="text-danger">*</span>', ['encode' => false]) ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <?= $form->field($model, 'start_date')->widget(DatePicker::class, [
                'options' => ['class' => 'form-control', 'id' => 'leave-request-start_date'],
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '2000:2050',
                ],
            ])->label('Start Date <span class="text-danger">*</span>', ['encode' => false]) ?>
        </div>

        <div class="form-group col-md-3">
            <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
                'options' => ['class' => 'form-control', 'id' => 'leave-request-end_date'],
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '2000:2050',
                ],
            ])->label('End Date <span class="text-danger">*</span>', ['encode' => false]) ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Working Days:</label>
            <input type="text" id="no_of_days" class="form-control" readonly>
        </div>
    </div>

    <div class="form-row my-3">
        <div class="form-group">
            <?= Html::submitButton('Submit WFH Request', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerCss("
    .ribbon {
        background: #333;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-group {
        flex: 1 1 30%;
        margin-bottom: 20px;
    }

    .form-value {
        margin-left: 10px;
        font-weight: normal;
    }

    @media (max-width: 768px) {
        .form-group {
            flex: 1 1 100%;
        }
        .ribbon {
            text-align: center;
        }
    }
");
?>

<?php
$script = <<<JS
function getWorkingDays(start, end) {
    const startDate = new Date(start);
    const endDate = new Date(end);
    let count = 0;

    while (startDate <= endDate) {
        const day = startDate.getDay();
        if (day !== 0 && day !== 6) count++;
        startDate.setDate(startDate.getDate() + 1);
    }
    return count;
}

function validateDates() {
    const start = $('#leave-request-start_date').val();
    const end = $('#leave-request-end_date').val();

    if (!start || !end) {
        alert('Please fill in both start and end dates.');
        return false;
    }

    const s = new Date(start);
    const e = new Date(end);

    if (s > e) {
        alert('End date cannot be before start date.');
        return false;
    }

    const days = getWorkingDays(start, end);
    $('#no_of_days').val(days);
    return true;
}

$('#leave-request-start_date, #leave-request-end_date').on('change', function () {
    validateDates();
});

$('#submit-button').on('click', function (e) {
    if (!validateDates()) {
        e.preventDefault();
    }
});
JS;
$this->registerJs($script);
?>

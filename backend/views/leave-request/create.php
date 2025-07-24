<?php

use backend\models\Department;
use backend\models\Employee;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\LeaveRequest */

$this->title = 'Create Leave Request';
$this->params['breadcrumbs'][] = ['label' => 'Leave Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Fetch dynamic data
$user = Yii::$app->user->identity;
$userName = $user->username ?? 'Unknown';
$userId = Yii::$app->user->id;

// Find the employee based on user_id
$employee = Employee::findOne(['user_id' => $userId]);

$preferredFullName = $employee->preferred_full_name ?? 'N/A';
$remainingLeaveDays = $employee->annual_leave ?? 'N/A';

// Fetch department
$department = $employee ? Department::findOne($employee->department_id) : null;

// Fetch department manager ID from Department table
$departmentManagerId = $department->department_manager ?? null;

// Fetch manager's username
$manager = $departmentManagerId ? User::findOne($departmentManagerId) : null;
$departmentManager = $manager->username ?? 'N/A';

$teamManager = 'N/A';
$teamManagerId = null;
if ($employee && $employee->team) {
    $team = \backend\models\Team::findOne($employee->team); // or tbl_teams
    if ($team && $team->team_manager) {
        $teamManagerUser = User::findOne($team->team_manager);
        $teamManager = $teamManagerUser->fullname ?? 'N/A';
        $teamManagerId = $teamManagerUser->id ?? null;
    }
}
?>

<div class="leave-request-create">
    <?php if ($employee): ?>
    <?php $form = ActiveForm::begin(['id' => 'leave-request-form']); ?>

    <!-- Ribbon Information Section -->
    <div class="ribbon">
        <span><b>Information:</b> </span>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Preferred Full Name:</label><span style="margin-left: 10px;"><?= Html::encode($preferredFullName) ?></span><br>
            <label>Department Manager:</label><span style="margin-left: 10px;"><?= Html::encode($departmentManager) ?></span><br>
            <label>Team Manager:</label><span style="margin-left: 10px;"><?= Html::encode($teamManager) ?></span><br>
            <label>Remaining Leave Days:</label><span style="margin-left: 10px;"><?= Html::encode($remainingLeaveDays) ?></span><br>
            <?= Html::hiddenInput('department_manager', $departmentManagerId); ?>
            <?= Html::hiddenInput('team_manager', $teamManagerId); ?>
        </div>
    </div>

    <h4><b><?= Html::encode($this->title) ?></b></h4>
    
    <div class="form-row">
        <?= $form->field($model, 'employee_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
    </div>
    <?= Html::hiddenInput('department_manager', $departmentManagerId) ?>
   
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
    <div class="form-row">

 <div class="form-group col-md-4">
    <label>Number of Leave Days (Working Days):</label>
    <input type="text" id="no_of_days" class="form-control" readonly>
</div>

</div>
    <!-- Submit Button -->
    <div class="form-row">
        <div class="form-group col-md-4">
            <?= Html::submitButton('Create Leave Request', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php else: ?>
    <div class="alert alert-warning">
         <strong>Profile Incomplete:</strong> Your employee profile has not been created yet. Please contact the HR department to set it up before submitting a leave request.
    </div>
<?php endif; ?>
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
#no_of_days {
  max-width: 200px; /* or any width you want */
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
<script>
function getWorkingDays(startDateStr, endDateStr) {
    const start = new Date(startDateStr);
    const end = new Date(endDateStr);

    if (isNaN(start) || isNaN(end)) return 0;

    let count = 0;
    const current = new Date(start);

    while (current <= end) {
        const day = current.getDay();
        // Day 0 = Sunday, Day 6 = Saturday
        if (day !== 0 && day !== 6) {
            count++;
        }
        current.setDate(current.getDate() + 1);
    }
    return count;
}

$('#leave-request-start_date, #leave-request-end_date').on('change', function () {
    const startDate = $('#leave-request-start_date').val();
    const endDate = $('#leave-request-end_date').val();

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (start > end) {
            $('#no_of_days').val('');
            alert('End date cannot be before start date.');
        } else {
            const days = getWorkingDays(startDate, endDate);
            $('#no_of_days').val(days);
        }
    }
});
</script>

<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;

// Fetch the latest processed payroll record
$lastProcessed = (new Query())
    ->select(['payroll_year', 'payroll_month'])
    ->from('tbl_payroll_run')
    ->orderBy(['id' => SORT_DESC]) // Assuming 'id' is the primary key
    ->limit(1)
    ->one();

// Determine the next payroll month after the last processed month
if ($lastProcessed) {
    $nextPayrollYear = $lastProcessed['payroll_year'];
    $nextPayrollMonth = $lastProcessed['payroll_month'] + 1;

    if ($nextPayrollMonth > 12) {
        $nextPayrollMonth = 1;
        $nextPayrollYear += 1;
    }
} else {
    // If no records found, default to the current month
    $nextPayrollYear = date('Y');
    $nextPayrollMonth = date('n');
}

// Fetch all processed payrolls
$processedRecords = (new Query())
    ->select(['payroll_year', 'payroll_month'])
    ->from('tbl_payroll_run')
    ->all();

// Create an array to store processed months
$processed = [];
foreach ($processedRecords as $record) {
    $processed[$record['payroll_year'] . '-' . $record['payroll_month']] = true;
}

// Month List
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

// Prepare dropdown options to disable processed and past months
$options = [];
foreach ($months as $month => $name) {
    $key = $nextPayrollYear . '-' . $month;

    // Disable past months and already processed months
    $isPastMonth = ($nextPayrollYear < date('Y') || ($nextPayrollYear == date('Y') && $month < date('n')));
    $isProcessed = isset($processed[$key]);

    $isDisabled = ($isPastMonth || $isProcessed) ? true : false;

    $options[$month] = $isDisabled ? ['disabled' => true] : [];
}
?>

<div class="payroll-processing">
    <h1><?= Html::encode('Payroll Processing') ?></h1>

    <div class="row">
        <div class="col-md-4">
            <label>Payroll Year</label>
            <?= Html::dropDownList('payroll_year', $nextPayrollYear, [$nextPayrollYear => $nextPayrollYear, $nextPayrollYear + 1 => $nextPayrollYear + 1], [
                'class' => 'form-control',
                'id' => 'payroll-year'
            ]) ?>
        </div>
        <div class="col-md-4">
            <label>Payroll Month</label>
            <?= Html::dropDownList('payroll_month', $nextPayrollMonth, $months, [
                'class' => 'form-control',
                'id' => 'payroll-month',
                'options' => $options
            ]) ?>
        </div>
        <div class="col-md-4" style="margin-top: 25px;">
            <?= Html::button('Load Payroll', [
                'class' => 'btn btn-primary',
                'id' => 'load-payroll-btn'
            ]) ?>
        </div>
    </div>

    <br>

    <div id="payroll-data"></div>
</div>

<?php
$fetchPayrollUrl = Url::to(['payroll-run/fetch-employee-data']);
$script = <<< JS
$('#load-payroll-btn').click(function() {
    let year = $('#payroll-year').val();
    let month = $('#payroll-month').val();

    if (!year || !month) {
        alert('Please select both year and month.');
        return;
    }

    $.ajax({
        url: "{$fetchPayrollUrl}",
        type: 'POST',
        data: { year: year, month: month },
        success: function(response) {
            $('#payroll-data').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Error fetching payroll data.');
        }
    });
});
JS;
$this->registerJs($script);
?>

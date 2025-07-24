<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $payrollMonth */
/** @var $payrollYear */
/** @var $employee */
/** @var $payrollRunId */
?>

<h3>Reopen Payroll - <?= Html::encode("$payrollMonth / $payrollYear") ?></h3>

<input type="hidden" id="payroll_month" value="<?= Html::encode($payrollMonth) ?>">
<input type="hidden" id="payroll_year" value="<?= Html::encode($payrollYear) ?>">
<input type="hidden" id="payroll_run_id" value="<?= Html::encode($payrollRunId) ?>">

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>Basic Salary</th>
            <th>Gross Salary</th>
            <th>Sales Commission</th>
            <th>Bonus</th>
            <th>Damages</th>
            <th>Absence</th>
            <th>Loan</th>
            <th>Net Salary</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employee as $emp): ?>
            <tr data-employee-id="<?= Html::encode($emp->employee_id) ?>" data-detail-id="<?= Html::encode($emp->id) ?>">
                <td><?= Html::encode($emp->employee->first_name ?? 'N/A') ?></td>
                <td class="basic_salary"><?= Html::encode($emp->basic_salary) ?></td>
                <td class="gross_salary"><?= Html::encode($emp->gross_salary) ?></td>
                <td contenteditable="true" class="editable" data-field="sales_commission"><?= Html::encode($emp->sales_commission ?? '0.00') ?></td>
                <td contenteditable="true" class="editable" data-field="bonus"><?= Html::encode($emp->bonus ?? '0.00') ?></td>
                <td contenteditable="true" class="editable" data-field="damages"><?= Html::encode($emp->damages ?? '0.00') ?></td>
                <td contenteditable="true" class="editable" data-field="absence"><?= Html::encode($emp->absence ?? '0.00') ?></td>
                <td contenteditable="true" class="editable" data-field="employee_loan"><?= Html::encode($emp->employee_loan ?? '0.00') ?></td>
                <td class="net_salary"><?= Html::encode($emp->net_salary ?? '0.00') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$updateUrl = Url::to(['payroll-run/update-inline']);
$this->registerJs(<<<JS
function calculateNet(row) {
    let basic = parseFloat(row.find('.basic_salary').text()) || 0;
    let gross = parseFloat(row.find('.gross_salary').text()) || 0;

    let sales = parseFloat(row.find('[data-field="sales_commission"]').text()) || 0;
    let bonus = parseFloat(row.find('[data-field="bonus"]').text()) || 0;
    let damages = parseFloat(row.find('[data-field="damages"]').text()) || 0;
    let absence = parseFloat(row.find('[data-field="absence"]').text()) || 0;
    let loan = parseFloat(row.find('[data-field="employee_loan"]').text()) || 0;

    let net = gross + sales + bonus - (damages + absence + loan);
    row.find('.net_salary').text(net.toFixed(2));
}

// Initial calculation
$('tbody tr').each(function() {
    calculateNet($(this));
});

$(document).on('blur', '.editable', function() {
    let td = $(this);
    let row = td.closest('tr');
    let field = td.data('field');
    let value = parseFloat(td.text()) || 0;
    let detailId = row.data('detail-id');

    calculateNet(row);

    $.ajax({
        url: '$updateUrl',
        type: 'POST',
        data: {
            detail_id: detailId,
            field: field,
            value: value,
            _csrf: yii.getCsrfToken()
        },
        success: function(res) {
            if (!res.success) {
                alert('Update failed: ' + res.message);
            }
        },
        error: function() {
            alert('Server error during update.');
        }
    });
});
JS);
?>

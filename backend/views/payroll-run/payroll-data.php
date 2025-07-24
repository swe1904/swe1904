<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="form-group"style="display: none;">
    <?= Html::label('Payroll Month', 'payroll_month') ?>
    <?= Html::dropDownList('payroll_month', $payrollMonth, array_combine(range(1, 12), range(1, 12)), [
        'id' => 'payroll_month',
        'class' => 'form-control'
    ]) ?>
</div>

<div class="form-group"style="display: none;">
    <?= Html::label('Payroll Year', 'payroll_year') ?>
    <?= Html::textInput('payroll_year', $payrollYear, [
        'id' => 'payroll_year',
        'class' => 'form-control',
        'readonly' => true
    ]) ?>
</div>

<!-- Employee Payroll Table -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Position</th>
            <th>Basic Salary</th>
            <th>Housing</th>
            <th>Transportation</th>
            <th>Gross Monthly Salary</th>
            <th>Sales Commission</th>
            <th>Bonus</th>
            <th>Damages</th>
            <th>Social Insurance</th>
            <th>Income Tax</th>
            <th>Absence</th>
            <th>Employee Loan</th>
            <th>Net Salary</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $emp): ?>
            <tr data-employee-id="<?= $emp['user_id'] ?>">
                <td><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></td>
                <td><?= $emp['position'] ?></td>
                <td class="basic_salary"><?= $emp['monthly_salary_basic'] ?></td>
                <td class="housing_allowance">0.00</td>
                <td class="transportation_allowance">0.00</td>
                <td class="gross_salary"><?= $emp['monthly_salary_basic'] ?></td>
                <td contenteditable="true" class="sales_commission inline-edit" data-field="sales_commission">0.00</td>
                <td contenteditable="true" class="bonus inline-edit" data-field="bonus">0.00</td>
                <td contenteditable="true" class="damages inline-edit" data-field="damages">0.00</td>
                <td class="social_insurance">0.00</td>
                <td class="income_tax">0.00</td>
                <td contenteditable="true" class="absence inline-edit" data-field="absence">0.00</td>
                <td contenteditable="true" class="employee_loan inline-edit" data-field="employee_loan">0.00</td>
                <td class="net_salary">0.00</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= Html::button('Finalize Payrun', ['id' => 'savePayroll', 'class' => 'btn btn-success btn-lg']) ?>

<script>
$(document).ready(function() {
    // Function to calculate net salary
    function calculateNetSalary(row) {
        let basicSalary = parseFloat(row.find('.basic_salary').text()) || 0;
        let housing = parseFloat(row.find('.housing_allowance').text()) || 0;
        let transportation = parseFloat(row.find('.transportation_allowance').text()) || 0;
        let grossSalary = basicSalary + housing + transportation;

        let salesCommission = parseFloat(row.find('.sales_commission').text()) || 0;
        let bonus = parseFloat(row.find('.bonus').text()) || 0;
        let damages = parseFloat(row.find('.damages').text()) || 0;
        let absence = parseFloat(row.find('.absence').text()) || 0;
        let employeeLoan = parseFloat(row.find('.employee_loan').text()) || 0;

        let incomeTax = grossSalary * 0;
        let socialInsurance = grossSalary * 0;

        let netSalary = grossSalary + salesCommission + bonus - (incomeTax + socialInsurance + absence + damages + employeeLoan);
        row.find('.net_salary').text(netSalary.toFixed(2));
    }

    // Trigger net salary calculation on input changes
    $(document).on('input', '.inline-edit', function() {
        let row = $(this).closest('tr');
        calculateNetSalary(row);
    });

    // Calculate net salary for all employees on page load
    $('tr[data-employee-id]').each(function() {
        calculateNetSalary($(this));
    });

    // Save Payroll Data
    $('#savePayroll').on('click', function() {
        let payrollMonth = $('#payroll_month').val();
        let payrollYear = $('#payroll_year').val();

        if (!payrollMonth || !payrollYear) {
            alert('Please select payroll month and year.');
            return;
        }

        let payrollData = [];

        $('tr[data-employee-id]').each(function() {
            let employeeId = $(this).data('employee-id');
            payrollData.push({
                employee_id: employeeId,
                basic_salary: parseFloat($(this).find('.basic_salary').text()) || 0,
                housing_allowance: parseFloat($(this).find('.housing_allowance').text()) || 0,
                transportation_allowance: parseFloat($(this).find('.transportation_allowance').text()) || 0,
                gross_salary: parseFloat($(this).find('.gross_salary').text()) || 0,
                sales_commission: parseFloat($(this).find('.sales_commission').text()) || 0,
                bonus: parseFloat($(this).find('.bonus').text()) || 0,
                damages: parseFloat($(this).find('.damages').text()) || 0,
                social_insurance: parseFloat($(this).find('.social_insurance').text()) || 0,
                income_tax: parseFloat($(this).find('.income_tax').text()) || 0,
                absence: parseFloat($(this).find('.absence').text()) || 0,
                employee_loan: parseFloat($(this).find('.employee_loan').text()) || 0
            });
        });

        $.ajax({
            url: '<?= Url::to(["payroll-run/insert-payroll"]) ?>',
            type: 'POST',
            data: {
                payroll_month: payrollMonth,
                payroll_year: payrollYear,
                payroll_data: payrollData
            },
            success: function(response) {
                if (response.success) {
                    alert('Payroll data saved successfully!');
                    window.history.back();
                } else {
                    alert('Failed to save payroll data: ' + response.message);
                }
            },
            error: function() {
                alert('Error while saving payroll data.');
            }
        });
    });
});
</script>

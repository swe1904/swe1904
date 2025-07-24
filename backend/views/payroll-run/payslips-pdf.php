<?php
/* @var $employee array */
/* @var $payrollRun app\models\TblPayrollRun */
?>

<div style="text-align: center; border: 1px solid #000; padding: 20px;">
    <h2>Company Name: ABC Corp</h2>
    <h3>Payslip for <?= date('F Y', strtotime($payrollRun->payroll_month . ' ' . $payrollRun->payroll_year)) ?></h3>
    <p><strong>Employee:</strong> <?= $employee['first_name'] ?> <?= $employee['last_name'] ?></p>
    <p><strong>Position:</strong> <?= $employee['position'] ?></p>
    <hr>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <th style="border: 1px solid #000; padding: 8px;">Earnings</th>
            <th style="border: 1px solid #000; padding: 8px;">Amount (USD)</th>
        </tr>
        <tr>
            <td>Basic Salary</td>
            <td style="text-align: right;"><?= number_format($employee['basic_salary'], 2) ?></td>
        </tr>
        <tr>
            <td>Housing Allowance</td>
            <td style="text-align: right;"><?= number_format($employee['housing_allowance'], 2) ?></td>
        </tr>
        <tr>
            <td>Transportation Allowance</td>
            <td style="text-align: right;"><?= number_format($employee['transportation_allowance'], 2) ?></td>
        </tr>
        <tr>
            <td>Sales Commission</td>
            <td style="text-align: right;"><?= number_format($employee['sales_commission'], 2) ?></td>
        </tr>
        <tr>
            <td>Bonus</td>
            <td style="text-align: right;"><?= number_format($employee['bonus'], 2) ?></td>
        </tr>
        <tr>
            <th>Total Earnings</th>
            <th style="text-align: right;"><?= number_format($employee['gross_salary'], 2) ?></th>
        </tr>
    </table>

    <hr>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <th style="border: 1px solid #000; padding: 8px;">Deductions</th>
            <th style="border: 1px solid #000; padding: 8px;">Amount (USD)</th>
        </tr>
        <tr>
            <td>Social Insurance</td>
            <td style="text-align: right;"><?= number_format($employee['social_insurance'], 2) ?></td>
        </tr>
        <tr>
            <td>Income Tax</td>
            <td style="text-align: right;"><?= number_format($employee['income_tax'], 2) ?></td>
        </tr>
        <tr>
            <th>Total Deductions</th>
            <th style="text-align: right;"><?= number_format($employee['social_insurance'] + $employee['income_tax'], 2) ?></th>
        </tr>
    </table>

    <hr>
    <h3>Net Salary: $<?= number_format($employee['net_salary'], 2) ?></h3>
</div>

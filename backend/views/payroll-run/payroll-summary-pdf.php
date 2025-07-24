<?php
/** @var yii\web\View $this */
/** @var app\models\Payroll $payroll */
?>

<h2>Payroll Summary - <?= date('F Y', strtotime($payroll->payroll_year . '-' . $payroll->payroll_month)) ?></h2>
<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <tr>
        <th>Payroll Month</th>
        <th>Year</th>
        <th>No. of Employees</th>
        <th>Total Amount Paid</th>
        <th>Social Insurance</th>
        <th>Income Tax</th>
    </tr>
    <tr>
        <td><?= date('F', mktime(0, 0, 0, $payroll->payroll_month, 1)) ?></td>
        <td><?= $payroll->payroll_year ?></td>
        <td><?= $payroll->total_employees ?></td>
        <td><?= number_format($payroll->total_amount_paid, 2) ?></td>
        <td><?= number_format($payroll->total_social_insurance, 2) ?></td>
        <td><?= number_format($payroll->total_income_tax, 2) ?></td>
    </tr>
</table>

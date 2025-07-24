<?php
use yii\helpers\Html;
?>

<style>
    .payslip-container {
        width: 100%;
        font-family: Arial, sans-serif;
        border: 1px solid #000;
        padding: 20px;
    }
    .header {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }
    .details, .salary-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .details th, .details td, .salary-table th, .salary-table td {
        border: 1px solid #000;
        padding: 8px;
    }
    .footer {
        margin-top: 20px;
        text-align: center;
        font-size: 12px;
        font-style: italic;
    }
</style>

<div class="payslip-container">
    <div class="header">
        <?= Html::encode(Yii::$app->name) ?><br>
        <small>Payslip for <?= Html::encode(date('F Y', strtotime($model->pay_period))) ?></small>
    </div>

    <table class="details">
        <tr>
            <th>Employee Name</th>
            <td><?= Html::encode($employee->first_name . ' ' . $employee->last_name) ?></td>
            <th>Employee ID</th>
            <td><?= Html::encode($employee->employee_id) ?></td>
        </tr>
        <tr>
            <th>Position</th>
            <td><?= Html::encode($employee->position) ?></td>
            <th>Department</th>
            <td><?= Html::encode($employee->department_id) ?></td>
        </tr>
        <tr>
            <th>Pay Period</th>
            <td><?= Html::encode(date('F Y', strtotime($model->pay_period))) ?></td>
            <th>Salary Currency</th>
            <td><?= Html::encode($employee->salary_currency_id) ?></td>
        </tr>
    </table>

    <table class="salary-table">
        <thead>
            <tr>
                <th>Earnings</th>
                <th>Amount</th>
                <th>Deductions</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td><?= Html::encode(number_format($model->basic_salary, 2)) ?></td>
                <td>Tax</td>
                <td><?= Html::encode(number_format($model->tax_deductions, 2)) ?></td>
            </tr>
            <tr>
                <td>Allowances</td>
                <td><?= Html::encode(number_format($model->allowances, 2)) ?></td>
                <td>Other Deductions</td>
                <td><?= Html::encode(number_format($model->deductions, 2)) ?></td>
            </tr>
            <tr>
                <th>Total Earnings</th>
                <th><?= Html::encode(number_format($model->basic_salary + $model->allowances, 2)) ?></th>
                <th>Total Deductions</th>
                <th><?= Html::encode(number_format($model->tax_deductions + $model->deductions, 2)) ?></th>
            </tr>
            <tr>
                <th colspan="3">Net Salary</th>
                <th><?= Html::encode(number_format($model->net_salary, 2)) ?></th>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        This is a computer-generated document. No signature required.
    </div>
</div>

<?php
use yii\helpers\Html;
?>

<style>
    body { font-family: sans-serif; font-size: 12px; }
    .header, .footer { text-align: center; }
    .company-name { font-size: 14px; font-weight: bold; }
    .line { border-top: 1px solid #000; margin: 5px 0; }

    .section-title {
        font-weight: bold;
        margin-bottom: 5px;
        margin-top: 10px;
        text-decoration: underline;
    }

    .info-table, .salary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    .info-table td {
        padding: 3px;
    }

    .salary-table th, .salary-table td {
        border: 1px solid #000;
        padding: 4px;
        text-align: left;
    }

    .total-row {
        font-weight: bold;
    }

    .netpay {
        margin-top: 10px;
        font-weight: bold;
    }

    .note {
        font-size: 10px;
        margin-top: 30px;
        text-align: center;
        color: gray;
    }
</style>

<div class="header">
    <?php $imageUrl = getenv('BACKEND_URL') . 'images/Northman-logo.png'; ?>
    <img src="<?= $imageUrl ?>" style="height: 40px;" />
    <div class="company-name">NORTHMAN & STERLING</div>
    <div>Olaya Towers, Tower B, Level 29, Riyadh, Saudi Arabia</div>
    <div><strong>Payslip for the month of <?= Html::encode($monthName) ?>, <?= Html::encode($year) ?></strong></div>
</div>

<div class="line"></div>

<table class="info-table">
    <tr>
        <td><strong>Employee Name</strong>: <?= Html::encode($employee['employee_name'] ?? '') ?></td>
        <td><strong>Employee No.</strong>: <?= Html::encode($employee['employee_id'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Position</strong>: <?= Html::encode($employee['position'] ?? 'N/A') ?></td>
        <td><strong>Bank Name</strong>: <?= Html::encode($employee['bank_name'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <td><strong>Department</strong>: <?= Html::encode($employee['department_id'] ?? 'N/A') ?></td>
        <td><strong>Bank Account No.</strong>: <?= Html::encode($employee['bank_account_no'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <td><strong>Location</strong>: <?= Html::encode($employee['location'] ?? 'N/A') ?></td>
        <td><strong>Effective Work Days</strong>: <?= Html::encode($employee['effective_days'] ?? '0') ?></td>
    </tr>
    <tr>
        <td><strong>LOP</strong>: <?= Html::encode($employee['lop'] ?? '0') ?></td>
        <td><strong>Leave Balance</strong>: <?= Html::encode($employee['leave_balance'] ?? '0') ?></td>
    </tr>
</table>

<table class="salary-table">
    <thead>
        <tr>
            <th>Particulars</th>
            <th>Amount (INR)</th>
            <th>Particulars</th>
            <th>Amount (INR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Basic</td>
            <td><?= number_format($employee['basic_salary'] ?? 0, 2) ?></td>
            <td>Provident Fund</td>
            <td><?= number_format($employee['social_insurance'] ?? 0, 2) ?></td>
        </tr>
        <tr>
            <td>HRA</td>
            <td><?= number_format($employee['housing_allowance'] ?? 0, 2) ?></td>
            <td>Professional Tax</td>
            <td><?= number_format($employee['income_tax'] ?? 0, 2) ?></td>
        </tr>
        <tr>
            <td>Transportation</td>
            <td><?= number_format($employee['transportation_allowance'] ?? 0, 2) ?></td>
            <td>Damages</td>
            <td><?= number_format($employee['damages'] ?? 0, 2) ?></td>
        </tr>
        <tr>
            <td>Sales Commission</td>
            <td><?= number_format($employee['sales_commission'] ?? 0, 2) ?></td>
            <td>Absence Deduction</td>
            <td><?= number_format($employee['absence'] ?? 0, 2) ?></td>
        </tr>
        <tr>
            <td>Bonus</td>
            <td><?= number_format($employee['bonus'] ?? 0, 2) ?></td>
            <td>Loan</td>
            <td><?= number_format($employee['employee_loan'] ?? 0, 2) ?></td>
        </tr>
        <tr class="total-row">
            <td>Total Earnings</td>
            <td>
                <?= number_format(
                    ($employee['basic_salary'] ?? 0) +
                    ($employee['housing_allowance'] ?? 0) +
                    ($employee['transportation_allowance'] ?? 0) +
                    ($employee['sales_commission'] ?? 0) +
                    ($employee['bonus'] ?? 0), 2) ?>
            </td>
            <td>Total Deductions</td>
            <td>
                <?= number_format(
                    ($employee['social_insurance'] ?? 0) +
                    ($employee['income_tax'] ?? 0) +
                    ($employee['absence'] ?? 0) +
                    ($employee['damages'] ?? 0) +
                    ($employee['employee_loan'] ?? 0), 2) ?>
            </td>
        </tr>
    </tbody>
</table>

<div class="netpay">
    Net Pay for the Month: <strong><?= number_format($netSalary ?? 0, 2) ?></strong><br>
    <em><?= Html::encode($netPayWords ?? '') ?></em>
</div>

<div class="note">
    This is a system-generated payslip and does not require a signature.
</div>

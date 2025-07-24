<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Payslip Months';
$this->params['breadcrumbs'][] = $this->title;
// print_r($_SESSION);
?>

<h3><?= Html::encode($this->title) ?></h3>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>S. No.</th>
            <th>Month</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($payslips as $payslip): ?>
            <?php 
                $month = date("m", strtotime($payslip->payslip_date));
                $year = date("Y", strtotime($payslip->payslip_date));
                $monthYear = date("F Y", strtotime($payslip->payslip_date));
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $monthYear ?></td>
                <td>
                   <?= Html::a('<i class="bi bi-download"></i>  ', [
    'payslip/download',
    'id' => $payslip->id,
], ['class' => 'btn btn-primary btn-sm']) ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

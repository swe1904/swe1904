<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Payroll Management';
?>
<div class="payroll-run-index">
    <h3><?= Html::encode($this->title) ?></h3><br>
    <p>
        <?= Html::a('Create New Payroll', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'payroll_month',
                'label' => 'Payroll Month',
                'value' => function ($model) {
                    return date('F', mktime(0, 0, 0, $model->payroll_month, 1));
                },
            ],
            'payroll_year:text:Payroll Year',
            'total_employees:text:No. of Employees',
            'total_amount_paid:text:Total Amount Paid',
            'total_social_insurance:text:Total Social Insurance Liability',
            'total_income_tax:text:Total Income Tax Liability',
            [
                'label' => 'Download Documents',
                'format' => 'raw',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center;'],
               'value' => function ($model) {
    return '<div style="display: flex; gap: 3px; justify-content: center;">' .
    Html::a(
        '<i class="fas fa-undo"></i> Reopen',
        ['reopen-payroll', 'payrollRunId' => $model->id],
        [
            'class' => 'btn btn-warning btn-xs',
            'style' => 'background-color: #F9E79F; border-color: #F9E79F; color: black; font-size: 12px; padding: 3px 6px;',
            'data-confirm' => 'Are you sure you want to reopen this payroll?',
        ]
    ).
        Html::a(
            '<i class="fas fa-file-alt"></i> Payroll Summary', 
            ['download-summary', 'id' => $model->id], 
            [
                'class' => 'btn btn-info btn-xs', 
                'style' => 'background-color: #AED6F1; border-color: #AED6F1; color: black; font-size: 12px; padding: 3px 6px;'
            ]
        ) .
        Html::a(
            '<i class="fas fa-file-invoice"></i> Payslips', 
            ['payroll-run/download-payslips', 'payrollRunId' => $model->id], 
            [
                'class' => 'btn btn-success btn-xs', 
                'style' => 'background-color: #A9DFBF; border-color: #A9DFBF; color: black; font-size: 12px; padding: 3px 6px;'
            ]
        ) .
        Html::a(
            '<i class="fas fa-file-signature"></i> Tax Summary', 
            ['download-tax', 'id' => $model->id], 
            [
                'class' => 'btn btn-warning btn-xs', 
                'style' => 'background-color: #F9E79F; border-color: #F9E79F; color: black; font-size: 12px; padding: 3px 6px;'
            ]
        ) .
    '</div>';
},

            ],
        ],
    ]); ?>
</div>
<?php
$this->registerJs("
    $('.finalize-payroll').click(function() {
        if (!confirm('Are you sure you want to finalize this payroll?')) {
            return false;
        }
    });
");
?>

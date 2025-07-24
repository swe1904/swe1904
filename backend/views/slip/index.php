<?php

use app\components\GlobalConstant;
use backend\components\Helper;
use backend\models\Employee;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SlipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Salary Slips';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slip-index">

    <h2 class="mb-20"><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <a href="<?php echo Url::to(['create']) ?>"class="btn btn-success create-slip">Create Slip</a>
        <p class="text-right mb-20">
        <?= Html::dropDownList('month', Helper::previousMonthWords(), GlobalConstant::MONTHS_DROPDOWN, ['class'=>'form-control months', 'style'=>'display:inline;width: 100px', 'id' => 'months-dropdown'] ); ?> 
        <?= Html::dropDownList('year', Helper::previousMonthWords(), GlobalConstant::YEARS_DROPDOWN, ['class'=>'form-control years', 'style'=>'display:inline;width: 100px', 'id' => 'years-dropdown']); ?>

            <?php
            $getTotalPayoutURL = Url::to(['slip/get-total-payout']);
   $script = <<< JS
            $(function(){
                    $('#months-dropdown, #years-dropdown').on('change',function(){
                        var month = $('#months-dropdown').val();
                        var year = $('#years-dropdown').val();
                        $.ajax({
                            type: 'POST',
                            url: '$getTotalPayoutURL',
                            data: { 
                                month: month,
                                year: year
                            },
                            success: function(data) {
                                        let jsonData = JSON.parse(data)
                                        if (jsonData['totalPayout'] == 0) {
                                            toastr.warning('No salary slips available for given month and year');
                                        } 
                                        $('#total-payout').text('SAR ' + jsonData['totalPayout']);
                                        console.log(jsonData['slips']);
                                    },
                        });
                    })
                    
                    //inject with current year on runtime
                    $('#years-dropdown').val(new Date().getFullYear());

                    //on load execute change once:
                    $('#years-dropdown').change();

                })
    JS;
                $employeeName = ArrayHelper::map(Employee::find()->asArray()->all(), 'id', 'name');        
            ?>
            <?php $this->registerJs($script, yii\web\View::POS_READY); ?>
            
            
        <span>Total Payout: <span id="total-payout"></span></span>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


    [
    'attribute' => 'employee_id',
    'label' => 'Employee Name',
    // 'filter'=>ArrayHelper::map(Employee::find()->asArray()->all(), 'employee_id', 'name'),
    
    'value' => function ($model) use($employeeName){
        
        return $employeeName[$model->employee_id];
    },
    ],
            'payslip_month:ntext',
            'payslip_year',
            // 'leaves_left',
            'start_date',
            'end_date',
            // 'leaves_taken',
             // 'description',
             'current_salary',
            // 'deduction',
            [
                'label' => 'Currency',
                'value' => function($row) {
                    $employeeDynamicCurrencyID = Employee::findOne($row->employee_id)->currency_id;
                    $currencyID = backend\models\DynamicCurrency::findOne($employeeDynamicCurrencyID)->currency_id;
                    $currencyISO = backend\models\Currency::findOne($currencyID)->iso;
                    return $currencyISO;
                }
            ],
             'final_salary',
                  ['class' => 'yii\grid\ActionColumn',
                      'contentOptions'=>['style'=>'width:70px;']],

 [
    'class' => 'yii\grid\ActionColumn',
   'template' => '{my_button}',

'buttons' => [
     'my_button' => function ($url, $model, $key) {
         return Html::a('', ['slip/slip-pdf','id' => $model->id],['class' => 'glyphicon glyphicon-print','target'=>'_blank','slip/slip-pdf']);
    },
] ]

        ],
    ]); ?>
</div>

<?php

use backend\models\Country;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Employee;
use yii\jui\DatePicker;
use kartik\select2\Select2;
/** @var yii\web\View $this */
/** @var backend\models\BusinessTravel $model */
/** @var yii\widgets\ActiveForm $form */
?>
<style>
.select2-container--krajee .select2-selection--single {
    height: 40px !important;
    display: flex;
    align-items: center;
    border-radius: 6px !important;
}
.select2-selection__rendered {
    padding-top: 6px !important;
    padding-left: 10px;
}

</style>
<div class="business-travel-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'employee_id')->dropDownList(
                ArrayHelper::map(Employee::find()->all(), 'user_id', 'preferred_full_name'),
                ['prompt' => 'Select Employee']
            ) ?>
        </div>
        <div class="col-md-6">
            

              <?= $form->field($model, 'country')->label('Select Country')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(Country::find()->all(), 'id','country_name'),
                                    'language' => 'en',
                                    'options' => [
                                            'placeholder' => 'Select country',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            'label' => false,
                                        ],

                                        ])
                                        ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'from_date')->widget(DatePicker::class, [
                'options' => ['class' => 'form-control', 'id' => 'from_date'],
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1950:2050',
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_date')->widget(DatePicker::class, [
                'options' => ['class' => 'form-control', 'id' => 'to_date'],
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1950:2050',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
       <div class="col-md-6">
    <?= $form->field($model, 'reason')->textarea([
        'class' => 'form-control custom-textarea',
    ]) ?>
</div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS
    $('#to_date').change(function() {
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();

        if (fromDate && toDate && toDate < fromDate) {
            alert('End date cannot be before the start date.');
            $('#to_date').val(''); // Clear invalid date
        }
    });
JS);
?>

<style>
.custom-textarea {
    height: 150px !important;
    overflow-y: auto;
    resize: none; /* Optional: prevents manual resizing */
}

</style>

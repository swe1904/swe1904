<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DynamicCurrency $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dynamic-currency-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <div class="col-md-4">
            <?php 
                if ($this->context->route == 'dynamic-currency/create') {
                    echo $form->field($model, 'currency_id')->dropDownList($currencies, ['prompt' => 'Select Currency'])->label('Currency');
                } elseif ($this->context->route == 'dynamic-currency/update') {
                    echo $form->field($model, 'currency_id')->dropdownList([], ['prompt' => $currency, 'disabled' => 'disabled'])->label('Currency');
                }
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'conversion_rate_to_SAR')->textInput() ?>
        </div>
        <br>
    </div>

    <div class="col-md-12">
        <div class="form-group col-md-1">
            <?php $btnText = $this->context->route == 'dynamic-currency/create' ? 'Create' : 'Update' ?>
            <?= Html::submitButton($btnText, ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

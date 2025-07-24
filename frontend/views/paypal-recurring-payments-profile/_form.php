<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PaypalRecurringPaymentsProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paypal-recurring-payments-profile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'receipt_id')->textInput() ?>

    <?= $form->field($model, 'profileId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'profileStatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ack')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payerId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

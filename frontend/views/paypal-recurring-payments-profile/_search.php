<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PaypalRecurringPaymentsProfileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paypal-recurring-payments-profile-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'receipt_id') ?>

    <?= $form->field($model, 'profileId') ?>

    <?= $form->field($model, 'profileStatus') ?>

    <?= $form->field($model, 'ack') ?>

    <?php // echo $form->field($model, 'payerId') ?>

    <?php // echo $form->field($model, 'token') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

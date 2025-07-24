<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\search\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'first_name_fixed') ?>

    <?= $form->field($model, 'last_name_fixed') ?>

    <?= $form->field($model, 'phone_fixed') ?>

    <?= $form->field($model, 'address_fixed') ?>

    <?php // echo $form->field($model, 'text_1528808645886') ?>

    <?php // echo $form->field($model, 'select_1528809495736') ?>

    <?php // echo $form->field($model, 'date_1528809715690') ?>

    <?php // echo $form->field($model, 'date_1528810280939') ?>

    <div class="form-group">
        <?= Html::submitButton('', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

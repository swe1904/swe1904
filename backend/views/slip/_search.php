<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SlipSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slip-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'employee_id') ?>


    <?= $form->field($model, 'payslip_month') ?>

    <?= $form->field($model, 'payslip_year') ?>

    <?= $form->field($model, 'leaves_left') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'leaves_taken') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'current_salary') ?>

    <?php // echo $form->field($model, 'deduction') ?>

    <?php // echo $form->field($model, 'final_salary') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

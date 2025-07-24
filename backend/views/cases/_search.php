<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\CasesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'case_number') ?>

    <?= $form->field($model, 'case_type_id') ?>

    <?= $form->field($model, 'target_completion_date') ?>

    <?= $form->field($model, 'sending_country') ?>
    <!-- rohit saha-->
    <?= $form->field($model, 'created_at') ?>


    <?php // echo $form->field($model, 'receiving_country') ?>

    <?php // echo $form->field($model, 'applicant_last_name') ?>

    <?php // echo $form->field($model, 'applicant_first_name') ?>

    <?php // echo $form->field($model, 'date_of_birth') ?>

    <?php // echo $form->field($model, 'passport_number') ?>

    <?php // echo $form->field($model, 'mobile_number') ?>

    <?php // echo $form->field($model, 'office_address') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

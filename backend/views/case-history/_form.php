<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="case-history-form">

    <?php
//    echo "<pre>";
//    print_r($casesModel);
//    echo "</pre>";
//    die("die here");
    $form = ActiveForm::begin(); ?>

    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'case_id')->dropdownlist($data) ?>
    <?php } else { ?>
        <?= $form->field($model, 'case_id')->dropdownlist($data ,['disabled' => true]) ?>
    <?php } ?>
    <?= $form->field($model, 'case_time', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true,'value'=> date("Y-m-d h:i:sa",time())]) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mr-10']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

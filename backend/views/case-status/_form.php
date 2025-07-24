<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CaseStatus $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="case-status-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Status Name', ['class' => 'control-label']) ?>
                <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseStepsNotes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="case-steps-notes-form">


    <?php $form = ActiveForm::begin([
        'options' => ['data-pjax' => true],
    ]); ?>

<!--    --><?php //echo $form->field($model, 'case_steps_id')->textInput() ?>

<!--    --><?php //echo $form->field($model, 'user_id')->textInput() ?>

    <?php echo $form->field($model, 'description')->textarea(['rows' => 2])->label("New Note"); ?>

<!--    --><?php //echo $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>

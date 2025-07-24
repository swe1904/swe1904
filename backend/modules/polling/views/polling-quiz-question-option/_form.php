<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionOption */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="polling-quiz-question-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'polling_quiz_question_id')->textInput() ?>

    <?php echo $form->field($model, 'value')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'order')->textInput() ?>

    <?php echo $form->field($model, 'explanation')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

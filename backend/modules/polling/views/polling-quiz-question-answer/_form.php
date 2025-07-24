<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionAnswer */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="polling-quiz-question-answer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'participant_id')->textInput() ?>

    <?php echo $form->field($model, 'polling_quiz_question_id')->textInput() ?>

    <?php echo $form->field($model, 'answer')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

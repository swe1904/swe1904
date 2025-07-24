<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\search\base\PollingQuizQuestionAnswerSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="polling-quiz-question-answer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'participant_id') ?>

    <?php echo $form->field($model, 'polling_quiz_question_id') ?>

    <?php echo $form->field($model, 'answer') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

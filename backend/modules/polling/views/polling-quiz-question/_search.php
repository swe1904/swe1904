<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\search\base\PollingQuizQuestionSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="polling-quiz-question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'polling_quiz_id') ?>

    <?php echo $form->field($model, 'title') ?>

    <?php echo $form->field($model, 'question') ?>

    <?php echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'action_compare') ?>

    <?php // echo $form->field($model, 'action_compare_radio') ?>

    <?php // echo $form->field($model, 'action_compare_text') ?>

    <?php // echo $form->field($model, 'action_value') ?>

    <?php // echo $form->field($model, 'visible') ?>

    <?php // echo $form->field($model, 'visible_quiz_question_id') ?>

    <?php // echo $form->field($model, 'visible_compare') ?>

    <?php // echo $form->field($model, 'visible_value') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

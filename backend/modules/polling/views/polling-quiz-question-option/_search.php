<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\search\base\PollingQuizQuestionOptionSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="polling-quiz-question-option-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'polling_quiz_question_id') ?>

    <?php echo $form->field($model, 'value') ?>

    <?php echo $form->field($model, 'order') ?>

    <?php echo $form->field($model, 'explanation') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
